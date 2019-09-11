<?php

namespace App\Policies;

use App\Models\Issue;
use App\Models\Reminder;
use App\Models\User;
use App\Models\Comment;
use App\Models\Vehicle;
use App\Models\Vendor;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Input;
use App\Models\Service;

class CommentPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the comment.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Comment $comment
     * @return bool
     */
    public function get(User $user, Comment $comment)
    {
        //Allow getting any comment within same company
        return $user->company_id == $comment->user->company->id;

    }

    /**
     * Determine whether the user can create comments.
     *
     * @param  \App\Models\User $user
     * @return bool
     */
    public function create(User $user)
    {
        //We can create comments for items within same company
        $input = Input::all();
        $company_id = null;
       if (empty($input['parent_comment_id'])) {
           if ($input['type'] == 'issue') {
               $item = Issue::find($input['item_id']);
               $company_id = $item->vehicle->company_id;
           }

           if ($input['type'] == 'service') {
               $item = Service::find($input['item_id']);
               $company_id = $item->vehicle->company_id;
           }

           if ($input['type'] == 'reminder') {
               $item = !Reminder::find($input['item_id']);
               $company_id = $item->company_id;
           }

           if ($input['type'] == 'vehicle') {
               $item = Vehicle::find($input['item_id']);
               $company_id = $item->company_id;
           }

           if ($input['type'] == 'vendor') {
               $item = Vendor::find($input['item_id']);
               $company_id = $item->company_id;
           }
           return $user->company_id == $company_id;
       }else{
           $parent = Comment::find($input['parent_comment_id']);
           return $user->company_id == $parent->company_id;
       }


    }

    /**
     * Determine whether the user can update the comment.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Comment $comment
     * @return bool
     */
    public function update(User $user, Comment $comment)
    {
        //We can update only comments we own
        return $user->id == $comment->user_id;
    }

    /**
     * Determine whether the user can delete the comment.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Comment $comment
     * @return bool
     */
    public function delete(User $user, Comment $comment)
    {
        //we can delete only comments we own
        return $user->id == $comment->user_id;
    }
}
