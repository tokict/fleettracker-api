<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 19 Feb 2017 12:06:44 +0000.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Issue
 *
 * @property int $id
 * @property int $vehicle_id
 * @property \Carbon\Carbon $reported_at
 * @property \Carbon\Carbon $updated_at
 * @property string $summary
 * @property string $description
 * @property int $odometer
 * @property int $reported_by
 * @property int $assigned_to
 * @property string $status
 * @property int $company_id
 * @property int $submitted_by
 * @property string $photo_ids
 * @property string $document_ids
 * @property \Carbon\Carbon $created_at
 * @property Carbon $deleted_at
 * @property int $deleted_by
 *
 * @property \App\Models\Contact $reporter
 * @property \App\Models\Contact $assignee
 * @property \App\Models\User $submitter
 * @property \App\Models\Company $company
 * @property \App\Models\Vehicle $vehicle
 * @property User $deleter
 * @property \Illuminate\Database\Eloquent\Collection $media_links
 * @property \Illuminate\Database\Eloquent\Collection $comments
 *
 * @package App\Models
 */
class Issue extends Eloquent
{
    use SoftDeletes;
    public $timestamps = false;

    protected $casts = [
        'vehicle_id' => 'int',
        'odometer' => 'int',
        'reported_by' => 'int',
        'assigned_to' => 'int',
        'submitted_by' => 'int'
    ];

    protected $dates = [
        'reported_at'
    ];

    protected $fillable = [
        'vehicle_id',
        'reported_at',
        'summary',
        'description',
        'odometer',
        'reported_by',
        'assigned_to',
        'status',
        'submitted_by',
        'photo_ids',
        'document_ids'
    ];

    public function reporter()
    {
        return $this->belongsTo(\App\Models\Contact::class, 'reported_by');
    }

    public function vehicle()
    {
        return $this->belongsTo(\App\Models\Vehicle::class);
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    public function assignee()
    {
        return $this->belongsTo(\App\Models\Contact::class, 'assigned_to');
    }

    public function submitter()
    {
        return $this->belongsTo(\App\Models\User::class, 'submitted_by');
    }

    public function media_links()
    {
        return $this->hasMany(\App\Models\MediaLink::class);
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'item_id')->where('type', 'issue');
    }

    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getPublicData($excludeFields = [], $deep = true)
    {
        $issue = $this->toArray();
        unset(
            $issue['vehicle_id'],
            $issue['reported_by'],
            $issue['assigned_to'],
            $issue['submitted_by'],
            $issue['deleted_by'],
            $issue['deleted_at']

        );
        $issue['vehicle'] = !empty($this->getAttribute('vehicle') && !in_array('vehicle',
                $excludeFields)) ? $this->getAttribute('vehicle')->getPublicData([], false) : null;
        $issue['reporter'] = !empty($this->getAttribute('reporter') && !in_array('reporter',
                $excludeFields)) ? $this->getAttribute('reporter')->getPublicData([], false) : null;
        $issue['assignee'] = !empty($this->getAttribute('assignee') && !in_array('assignee',
                $excludeFields)) ? $this->getAttribute('assignee')->getPublicData([], false) : null;
        $issue['submitter'] = !empty($this->getAttribute('submitter')&& !in_array('submitter',
                $excludeFields)) ? $this->getAttribute('submitter')->getPublicData([], false) : null;
        $issue['photo_ids'] = !empty($this->getAttribute('photo_ids')) ? json_decode($this->getAttribute('photo_ids'),
            true) : [];
        $issue['document_ids'] = !empty($this->getAttribute('document_ids')) ? json_decode($this->getAttribute('document_ids'),
            true) : [];
        $issue['photos'] = [];

        if ($this->getAttribute('photo_ids')) {
            $photos = Medium::whereIn('id', json_decode($this->getAttribute('photo_ids'), true))->get();
            if ($photos) {
                foreach ($photos as $photo) {
                    $issue['photos'][] = [
                        'title' => $photo->title,
                        'description' => $photo->description,
                        'url' => $photo->getPublicPath('medium'),
                        'fileId' => $photo->id,
                        'type' => $photo->type
                    ];
                }
            }
        }


        $issue['documents'] = [];
        if ($this->getAttribute('document_ids')) {
            $docs = Medium::whereIn('id', json_decode($this->getAttribute('document_ids'), true))->get();
            if ($docs) {
                foreach ($docs as $doc) {
                    $issue['documents'][] = [
                        'title' => $doc->title,
                        'description' => $doc->description,
                        'url' => $doc->getPublicPath('medium'),
                        'fileId' => $doc->id,
                        'created_at' => $doc->created_at,
                        'size' => $doc->size,
                        'type' => $doc->type
                    ];
                }
            }
        }

        $issue['comments'] = [];
        if ($this->getAttribute('comments')) {
            foreach ($this->getAttribute('comments') as $comment) {
                $issue['comments'][] = $comment->getPublicData();

            }
        }



		return $issue;
	}
}
