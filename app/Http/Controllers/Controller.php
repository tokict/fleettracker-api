<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $User;


    public function __construct(Request $request)
    {

        $token = Request::header('Token');
        $this->User = User::where('remember_token', $token)->get()->first();
        if (!empty(Request::header('company')) && Request::header('company') != 'null' && (isset($this->User) && $this->User->super_admin == 1)){
        $this->User->company_id = Request::header('company');
    }

        if ($this->User) {
            Auth::setUser($this->User);
        }


    }

    /**
     * Take request input and remove all params except allowed ones passed
     * @param array $allowed
     * @return array
     */
    public function filterParams($allowed = array())
    {
        $ret = [];
        $all_keys = array_keys($allowed);
        foreach (Input::all() as $key => $p) {
            if (in_array($key, $all_keys)) {
                $ret[$key] = Input::all()[$key];
            }
        }
        return $ret;

    }

    /**
     * @param array $params
     * @param string $model Name of the model to use
     * @param array $ownCheck Params needed to check ownership
     * @return \Illuminate\Http\JsonResponse
     */
    public function doSearch($params = [], $model)
    {
        $classname = '\\App\\Models\\' . $model;
        $Model = new $classname();
        $builder = null;
        //Check if the key is actually a relation. If it is add it to query using ::with
        foreach ($params as $key => $values) {
            if (method_exists($Model, $key) && in_array($key, $Model->getFillable())) {
                $builder = $classname::with(ucfirst($key));
            }
        }
        //Lets check if this model has deleted_at property. If not just instantiate builder with id check
        if (property_exists($Model, 'deleted_at')) {
            if (!$builder) {
                $builder = $classname::whereNull('deleted_at');
            } else {
                $builder->whereNull('deleted_at');
            }
        } else {
            if (!$builder) {
                $builder = $classname::whereNotNull('id');
            } else {
                $builder->whereNotNull('id');
            }
        }


        $ordCol = null;
        $ordDir = null;
        $builder->orderBy('id', 'desc');
        foreach ($params as $key => $values) {
            if ($key == 'order') {
                continue;
            }
            if (in_array($key, $Model->getFillable())) {
                //If its an relation, we need to use params one level down
                if (method_exists($Model, $key)) {
                    foreach ($values as $k => $v) {
                        $op = $v['op'];
                        switch ($op) {
                            case 'is':
                                $builder->whereHas($key, function ($x) use ($v, $k) {
                                    $x->where($k, $v);

                                });
                                break;
                            case 'not':
                                $builder->whereHas($key, function ($x) use ($v, $k) {
                                    $x->where($k, '!=', $v['value']);
                                });

                                break;
                            case 'more':
                                $builder->whereHas($key, function ($x) use ($v, $k) {
                                    $x->where($k, '>', $v['value']);
                                });
                                break;
                            case 'like':
                                $builder->whereHas($key, function ($x) use ($v, $k) {
                                    $x->where($k, 'like', '%' . $v['value'] . '%');
                                });
                                break;
                            case 'less':
                                $builder->whereHas($key, function ($x) use ($v, $k) {
                                    $x->where($k, '<', $v['value']);
                                });
                                break;
                            case 'has':
                                $builder->whereHas($key, function ($x) use ($v, $k) {
                                    $x->has($k);
                                });
                                break;

                            default:
                                break;
                        }

                    }

                } else {
                    $op = $values['op'];

                    switch ($op) {
                        case 'is':
                            if ($values['value'] == 'null') {
                                $builder->whereNull($key);
                            } else {
                                $builder->where($key, $values['value']);
                            }

                            break;
                        case 'not':
                            if ($values['value'] == 'null') {
                                $builder->whereNotNull($key);
                            } else {
                                $builder->where($key, '!=', $values['value']);
                            }
                            break;
                        case 'more':
                            $builder->where($key, '>', $values['value']);
                            break;
                        case 'less':
                            $builder->where($key, '<', $values['value']);
                            break;
                        case 'has':
                            $builder->has($key);
                            break;
                        case 'like':
                            $builder->where($key, 'like', '%' . $values['value'] . '%');
                            break;
                        default:
                            break;
                    }
                }


            }
        }

        if ($model != 'Company' && \Schema::hasColumn($Model->getTable(), 'company_id')) {
            $builder->where('company_id', $this->User->company_id);
        }

        if (isset($params['order'])) {
            $parts = explode('.', $params['order']); //format is: column.direction
            if (count($parts)) {
                $ordCol = isset($parts[0]) ? $parts[0] : null;
                $ordDir = isset($parts[1]) ? $parts[1] : null;

                $builder->orderby($ordCol, $ordDir);
            }
        }


        $res = $builder->get();
        $data = [];
        if ($res->count()) {
            foreach ($res as $v) {
                if($this->User->can('get', $v)) {

                    $data[] = $v->getPublicData([], true);
                }

            }
        }

        return response()->json(
            [
                'data' => $data,

            ]

            , 200);

    }

    public static function getEnumOptions($table, $field)
    {
        $type = DB::select(DB::raw('SHOW COLUMNS FROM ' . $table . ' WHERE Field = "' . $field . '"'))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $values = array();
        foreach (explode(',', $matches[1]) as $value) {
            $values[] = trim($value, "'");
        }
        return $values;
    }
}
