<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Reminder;
use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


class CommentController extends Controller
{


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $val = [
            'type' => 'required_without:parent_comment_id|max:45',
            'title' => 'max: 100|nullable',
            'text' => 'required|max:1000',
            'parent_comment_id' => 'numeric|nullable|exists:comments,id',
            'item_id' => 'numeric|required_without:parent_comment_id',
        ];
        $this->validate($request, $val);


        $input = $this->filterParams($val);


        if (!$this->User->can('create', Comment::class)) {
            abort(500);
        }
        if (empty($input['parent_comment_id'])) {
            //Lets check that the item id exists
            if ($input['type'] == 'issue') {
                $item = Issue::find($input['item_id']);
                if (!$item) {
                    return response()->json([
                        'error' => 'The item does not exist'
                    ], 422);
                }
            }

            if ($input['type'] == 'service') {
                $item = Service::find($input['item_id']);
                if (!$item) {
                    return response()->json([
                        'error' => 'The item does not exist'
                    ], 422);
                }
            }

            if ($input['type'] == 'reminder') {
                $item = Reminder::find($input['item_id']);
                if (!$item) {
                    return response()->json([
                        'error' => 'The item does not exist'
                    ], 422);
                }
            }

            if ($input['type'] == 'vehicle') {
                $item = Vehicle::find($input['item_id']);
                if (!$item) {
                    return response()->json([
                        'error' => 'The item does not exist'
                    ], 422);
                }
            }

            if ($input['type'] == 'vendor') {
                $item = Vendor::find($input['item_id']);
                if (!$item) {
                    return response()->json([
                        'error' => 'The item does not exist'
                    ], 422);
                }
            }

            $comment = Comment::create($input);
            if ($comment) {
                return response()->json(
                    $item->getPublicData(), 201
                );
            } else {
                return response()->json([
                    'errors' => 'Could not save comment to db'
                ], 500);
            }
        } else {

            $comment = Comment::find($input['parent_comment_id']);
            if ($comment) {
                $reply = Comment::create([
                    'title' => !empty($input['title'])?empty($input['title']):null,
                    'text' => $input['text'],
                    'type' => $comment->type,
                    'item_id' => $comment->item_id,
                    'parent_comment_id' => $input['parent_comment_id'],

                ]);

                if ($reply) {
                    return response()->json(
                        $reply->getPublicData(), 201
                    );
                }
            } else {
                return response()->json([
                    'errors' => 'Could not save comment to db'
                ], 500);
            }
        }


    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {
        $this->validate($request, [
            'id' => 'numeric|nullable',
            'title' => 'max: 100|nullable',
            'item_id' => 'numeric|nullable'
        ]);
        $order = Input::get('order');
        $sort = Input::get('dir');
        $search = Input::get('search');

        $comment = null;
        $comments = [];

        //If item_id is set, search all comments
        if (!empty(Input::get('item_id')) && !empty(Input::get('type'))) {
            $data = Comment::where('item_id', Input::get('item_id'))
                ->where('type', Input::get('type'))
                ->whereNull('parent_comment_id')
                ->whereNull('deleted_at')
                ->where('company_id', $this->User->company_id);

            if ($search) {
                $data->where('text', 'like', '%' . Input::get('search') . '%');
            }

            if ($order) {
                $data->orderBy($order, $sort);
            }
            $res = $data->paginate(50);

            if ($res->count()) {
                foreach ($res as $c) {
                    if ($this->User->can('get', $c)) {
                        $comments[] = $c->getPublicData();
                    }
                }
            }

        } else {
            //We are searching specific comment
            if (!empty(Input::get('id'))) {
                $comment = Comment::where('id', Input::get('id'))
                    ->where('company_id', $this->User->company_id)
                    ->whereNull('deleted_at')
                    ->get()
                    ->first();
                if (!$this->User->can('get', $comment)) {
                    $comment = null;
                }
            }
        }
        if ($comment) {
            return response()->json([
                $comment->getPublicData()
            ], 200);
        } elseif ($comments) {
            return response()->json(
                [
                    'data' => $comments,
                    'total_items' => $res->total(),
                    'total_pages' => $res->lastPage(),
                    'per_page' => $res->perPage(),
                    'current_page' => $res->currentPage()
                ]

                , 200);
        } else {
            return response()->json([
                'error' => 'No comment with that id'
            ], 422);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $val = [
            'id' => 'required|numeric',
            'title' => 'nullable',
            'text' => 'max:1000',
        ];

        $this->validate($request, $val);

        $input = $this->filterParams($val);
        $comment = Comment::where('id', Input::get('id'))->whereNull('deleted_at')->get()->first();

        if ($comment) {
            if (!$this->User->can('update', $comment)) {
                abort(500);
            }
            if ($comment->update($input)) {
                return response()->json(
                    $comment->getPublicData()
                , 200);
            } else {
                return response()->json([
                    'error' => 'Could not update entry using provided parameters'
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'No entry with that id'
            ], 422);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric'
        ]);

        $comment = Comment::find(Input::get('id'));
        if (!$this->User->can('delete', $comment)) {
            abort(500);
        }
        if ($comment) {
            if ($comment->delete()) {
                return response()->json([
                    'success' => true
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Could not delete comment using provided parameters'
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'No comment with that id'
            ], 422);
        }
    }
}
