<?php

namespace App\Observers;


use App\Models\Comment;
use App\Models\Subscription;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CommentObserver
{

    /**
     * Listen to the Comment created event.
     *
     * @param  Comment  $comment
     * @return void | Response
     */
    public function creating(Comment $comment){


        $comment->user_id = Auth::user()->id;
        $comment->company_id = Auth::user()->company_id;

    }


    /**
     * Listen to the Comment created event.
     *
     * @param  Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        //If the user is making a comment on an item, subscribe him to his own comment so he gets updates when someone
        //else comments on the item. Also, subscribe him to replies to his comment untill he turns them off
        if(!$comment->parent_comment_id){
            $data = [
                'item_id' => $comment->id,
                'type' => 'comment',
                'user_id' => $comment->user_id
            ];

            Subscription::create($data);

            $data2 = [
                'item_id' => $comment->item_id,
                'type' => 'comment',
                'user_id' => $comment->user_id
            ];

            Subscription::create($data2);
        }

        //this is a reply to comment, subscribe user to its reply
        if($comment->parent_comment_id){
            $data = [
                'item_id' => $comment->item_id,
                'type' => 'comment',
                'user_id' => $comment->user_id
            ];
            Subscription::create($data);
        }
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  Comment  $comment
     * @return void
     */
    public function deleting(Comment $comment)
    {
        $comment->deleted_by = Auth::User()->id;

    }
}