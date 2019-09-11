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
 * @property int $item_id
 * @property string $type
 * @property int $contact_id
 * @property int $odo_start
 * @property int $odo_end
 * @property int $company_id
 * @property \Carbon\Carbon $started_at
 * @property \Carbon\Carbon $ended_at
 * @property \Carbon\Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property int $notify
 * @property int $deleted_by
 *
 * @property \App\Models\Contact $assignee
 * @property \App\Models\User $deleter
 * @property Collection $comments
 * @property $item
 *
 * @package App\Models
 */
class Assignment extends Eloquent
{
    use SoftDeletes;
    public $timestamps = false;


    protected $casts = [
        'item_id' => 'int',
        'contact_id' => 'int'
    ];
    protected $dates = [
        'deleted_at',
        'started_at',
        'ended_at'
    ];

    protected $fillable = [
        'item_id',
        'type',
        'contact_id',
        'started_at',
        'ended_at',
        'deleted_at',
        'notify',
        'odo_start',
        'odo_end'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    public function assignee()
    {
        return $this->belongsTo(\App\Models\Contact::class, 'contact_id');
    }

    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by');
    }

    public function contact()
    {
        return $this->belongsTo(\App\Models\Contact::class, 'deleted_by');
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'item_id')->where('type', 'comment');
    }


    public function item()
    {
        if ($this->getAttribute('type') == 'issue'){
            return $this->belongsTo(\App\Models\Issue::class, 'item_id');
        }

        if ($this->getAttribute('type') == 'service'){
            return $this->belongsTo(\App\Models\Service::class, 'item_id');
        }

        if ($this->getAttribute('type') == 'vehicle'){
            return $this->belongsTo(\App\Models\Vehicle::class, 'item_id');
        }
	}

    public function getPublicData($excludeFields = [], $deep = true)
    {
        $assigment = $this->toArray();
        unset(
            $assigment['item_id'],
            $assigment['contact_id'],
            $assigment['started_at']
        );
        $assigment['assignee'] = !empty($this->getAttribute('assignee')) ? $this->getAttribute('assignee')->getPublicData([], false) : null;
        $assigment['item'] = !empty($this->getAttribute('item') ) ? $this->getAttribute('item')->toArray() : null;
        $assigment['started_at'] = !empty($this->getAttribute('started_at')) ? $this->getAttribute('started_at')->format("Y-m-d H:i:s") : null;

        return $assigment;
    }
}
