<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 19 Feb 2017 12:06:44 +0000.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Comment
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $title
 * @property string $text
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $parent_comment_id
 * @property int $item_id
 * @property int $company_id
 * @property string $status
 * @property Carbon $deleted_at
 * @property int $deleted_by
 *
 * @property \App\Models\User $user
 * @property \App\Models\User $deleter
 * @property Comment $parent_comment
 * @property Collection $replies
 * @property $item
 *
 * @package App\Models
 */
class Comment extends Eloquent
{
    use SoftDeletes;


    protected $casts = [
        'user_id' => 'int',
        'parent_comment_id' => 'int',
        'item_id' => 'int'
    ];
    protected $dates = ['deleted_at', 'created_at'];

    protected $fillable = [
        'user_id',
        'type',
        'created_at',
        'title',
        'text',
        'parent_comment_id',
        'item_id',
        'company_id'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by');
    }

    public function parent_comment()
    {
        return $this->belongsTo(\App\Models\Comment::class);
    }

    public function replies()
    {
        return $this->hasMany(\App\Models\Comment::class, 'parent_comment_id');
    }


    public function item()
    {
        if ($this->getAttribute('type') == 'issue') {
            return $this->belongsTo(\App\Models\Issue::class, 'item_id');
        }

        if ($this->getAttribute('type') == 'service') {
            return $this->belongsTo(\App\Models\Service::class, 'item_id');
        }

        if ($this->getAttribute('type') == 'reminder') {
            return $this->belongsTo(\App\Models\Reminder::class, 'item_id');
        }
        if ($this->getAttribute('type') == 'vehicle') {
            return $this->belongsTo(\App\Models\Vehicle::class, 'item_id');
        }
        if ($this->getAttribute('type') == 'vendor') {
            return $this->belongsTo(\App\Models\Vendor::class, 'item_id');
        }
    }

    public function getPublicData($excludeFields = [], $deep = true)
    {
        $comment = $this->toArray();
        unset(
            $comment['user_id'],
            $comment['created_at'],
            $comment['deleted_at'],
            $comment['deleted_by'],
            $comment['status'],
            $comment['item_id'],
            $comment['item']
        );
        $comment['name'] = !empty($this->getAttribute('user')->contact) ? $this->getAttribute('user')->contact->first_name . ' ' . $this->getAttribute('user')->contact->last_name : null;
        $comment['pic'] = !empty($this->getAttribute('user')->contact->image) ? $this->getAttribute('user')->contact->image->getPublicPath('small') : null;
        $comment['posted_at'] = $this->getAttribute('created_at')->format("Y-m-d H:i:s");
        $comment['itemTitle'] = $this->getItemTitle();
        $comment['itemLink'] = $this->getItemLink();

        $comment['replies'] = [];
        if($deep) {
            foreach ($this->getAttribute('replies') as $r) {
                $comment['replies'] = $r->getPublicData();

            }
        }
            return $comment;

    }


    function getItemTitle()
    {

        if(get_class($this->getAttribute('item')) == 'App\Models\Issue') {
            return $this->getAttribute('item')->summary . " -> " . $this->getAttribute('item')->vehicle->name;
        }

        if (get_class($this->getAttribute('item')) == 'App\Models\Service') {
            return $this->getAttribute('item')->vehicle->name;
        }

        if (get_class($this->getAttribute('item')) == 'App\Models\Vehicle') {
            return $this->getAttribute('item')->name;
        }

        if (get_class($this->getAttribute('item')) == 'App\Models\Vendor') {
            return $this->getAttribute('item')->name;
        }




    }

    function getItemLink()
    {
        if (get_class($this->getAttribute('item')) == 'App\Models\Issue') {
            return '/#/'.\Auth::user()->id.'/issues/'.$this->getAttribute('item')->id;
        }

        if (get_class($this->getAttribute('item')) == 'App\Models\Service') {
            return '/#/'.\Auth::user()->id.'/vehicles/'.$this->getAttribute('item')->vehicle_id.'/service_entries/'.$this->getAttribute('item')->id;
        }

        if (get_class($this->getAttribute('item')) == 'App\Models\Vehicle') {
            return '/#/'.\Auth::user()->id.'/vehicles/'.$this->getAttribute('item')->id;
        }

        if (get_class($this->getAttribute('item')) == 'App\Models\Vendor') {
            return '/#/'.\Auth::user()->id.'/vendors/'.$this->getAttribute('item')->id;
        }


    }
}
