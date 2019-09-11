<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Medium;
use App\Models\Service;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\File;

class MediaController extends Controller
{

    protected $storage;

    public function __construct(\Illuminate\Support\Facades\Request $request)
    {
        parent::__construct($request);
        $this->storage = \Storage::disk('public');

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request, $category, $id)
    {

        $fails = 0;

        $file = $request->file()['file'];
        $media = new Medium([]);

        $save = $media->saveFile($file, $this->User->company_id . "/" . $category . "/" . $id, 'private');
        $type = "document";

        if (in_array($file->getClientOriginalExtension(), ['jpg', 'JPG', 'jpeg', 'png', 'PNG'])) {
            $type = 'image';
        }


        if ($save) {
            $media->setAttribute('reference', $save);
            $media->setAttribute('uploaded_by', $this->User->id);
            $media->setAttribute('type', $type);
            $media->setAttribute('directory', $category . "/" . $id);
            $media->setAttribute('size', $file->getClientSize());

            if ($media->save()) {

                $input['cover_photo_id'] = $media->id;
            } else {
                $fails++;
            }

        } else {
            $fails++;
        }

        if ($fails) {
            return response()->json(['success' => false], 500);
        }
        {
            return response()->json(
                [
                    'success' => true,
                    'file_id' => $media->id,
                    'path' => $media->getPath()
                ], 201);
        }

    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'id' => 'numeric',
        ]);

        if (!empty(Input::get('id'))) {
            $media = Medium::find(Input::get('id'));
            if (!$this->User->can('get', $media)) {
                abort(500);
            }

            if ($media) {
                if ($this->User->company_id != $media->company_id) {
                    return response()->json([
                        'error' => 'You are not authorized to access this resource'
                    ], 500);
                }
                return response()->json([
                    $media->toArray()
                ], 200);
            } else {
                return response()->json([
                    'error' => 'No media with that id'
                ], 422);
            }
        }
        //At this point if he did not search by id and there is no params, something is wrong
        $p = Input::get('params');
        $params = null;
        if ($p) {
            $params = json_decode($p, true);
        }

        if (is_array($params)) {
            return $this->doSearch($params, 'Media');
        } else {
            return response()->json([
                'error' => 'Malformed request'
            ], 422);
        }

    }

    public function show(Request $request, $directory, $company, $size, $filename)
    {

        $media = Medium::where('company_id', $company)->where('directory', $directory)->where('reference',
            $filename)->get()->first();

        if ($media) {
            $file = new \Symfony\Component\HttpFoundation\File\File($media->getPath($size));
            $mime = $file->getMimeType();


            return \Illuminate\Support\Facades\Response::make(file_get_contents($media->getPath($size)), 200, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . $media->reference . '"'
            ]);
        }

    }

    public function delete(Request $request)
    {

        $file = Medium::where('id', Input::get('id'))->first();

        if (!$this->User->can('delete', $file)) {
            abort(500);
        }


        if ($this->User->company_id != $file->company_id) {
            return response()->json([
                'error' => 'You are not authorized to access this resource'
            ], 500);
        }
        //File has no links and we can delete it from db and storage
        //Delete from storage

        $id = $file->id;
        if ($file->type == 'image') {
            if (
                $this->storage->delete($file->getPath('small'))
                && $this->storage->delete($file->getPath('medium'))
                && $this->storage->delete($file->getPath('large'))
                && $this->storage->delete($file->getPath('original'))
                && $file->delete()
            ) {

                $coll = null;
                if (strpos($file->directory, 'issues/') !== false) {
                    $coll = Issue::get();
                }
                if (strpos($file->directory, 'vehicles/') !== false) {
                    $coll = Vehicle::get();
                }
                if (strpos($file->directory, 'services/') !== false) {
                    $coll = Service::get();
                }


                if ($coll) {
                    foreach ($coll as $item) {
                        $arr = json_decode($item->photo_ids, true);
                        if ($arr && is_array($arr) && in_array($id, $arr)) {
                            unset($arr[array_search($id, $arr)]);
                            $item->photo_ids = json_encode($arr);
                            $item->save();
                        }
                    }
                }


                return response()->json(['success' => true]);
            }
        }else{
            if ($file->delete()
            ) {

                $coll = null;
                if (strpos($file->directory, 'issues/') !== false) {
                    $coll = Issue::get();
                }
                if (strpos($file->directory, 'vehicles/') !== false) {
                    $coll = Vehicle::get();
                }
                if (strpos($file->directory, 'services/') !== false) {
                    $coll = Service::get();
                }


                if ($coll) {
                    foreach ($coll as $item) {
                        $arr = json_decode($item->document_ids, true);
                        if ($arr && is_array($arr) && in_array($id, $arr)) {
                            unset($arr[array_search($id, $arr)]);
                            $item->document_ids = json_encode($arr);
                            $item->save();
                        }
                    }
                }


                return response()->json(['success' => true]);
            }
        }


        return response()->json(['success' => false], 500);


    }


    public function edit($request, $id)
    {
        $file = Medium::whereId($id)->first();

        if (!$this->User->can('edit', $file)) {
            abort(500);
        }
        if ($file->uploaded_by == $this->User->id) {

            $file->title = Input::get('title');
            $file->description = Input::get('description');

            if ($file->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }

    }


    public function open($request, $folder)
    {
        $filterType = Input::get("filterType", null);
        $filterType = $filterType == "all" ? null : $filterType;
        $sort = Input::get("sort", 'latest') == 'latest' ? 'DESC' : 'ASC';
        $page = Input::get("page", 1);
        $search = Input::get("search", null);
        $search = $search == "" ? null : $search;
        $folder = strtolower($folder);

        $folders['campaigns'] = [];
        $folders['beneficiaries'] = [];
        $folders['companies'] = [];
        $folders['people'] = [];
        $folders['documents '] = [];
        $folders['other'] = [];
        if (!isset($folders[$folder])) {
            die('Go away!');
        }

        /*CAMPAIGNS*/

        $data = Medium::where('uploaded_by', $this->User->id)
            ->where('directory', $folder);
        if (isset($search)) {
            $data->where('title', 'like', '%' . $search . '%');
        }

        if (isset($filterType)) {
            $data->where('type', $filterType);
        }
        $data->orderBy('created_at', $sort);
        $folders[$folder] = $data->paginate(30);


        return view('admin.common.filemanager', [
            'folders' => $folders,
            'active' => $folder,
            'type' => $filterType,
            'sort' => $sort,
            'search' => $search
        ]);
    }

    public function readDir()
    {
        return $this->storage->directories("");
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function options(Request $request)
    {

        return response()->json([
                'status' => parent::getEnumOptions('media', 'type')
            ]
            , 200);
    }


}
