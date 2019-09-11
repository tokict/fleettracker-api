<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 19 Feb 2017 12:06:44 +0000.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Service
 *
 * @property int $id
 * @property \Carbon\Carbon $serviced_at
 * @property int $vehicle_id
 * @property int $odometer
 * @property string $performed_service_types
 * @property string $performed_renewal_types
 * @property string $resolved_issues
 * @property int $vendor_id
 * @property string $reference
 * @property int $labor_price
 * @property int $parts_price
 * @property \Carbon\Carbon $created_at
 * @property int $company_id
 * @property int $tax
 * @property int $total
 * @property string $tax_type
 * @property int $created_by
 * @property Carbon $deleted_at
 * @property int $deleted_by
 * @property string $document_ids
 * @property string $photo_ids
 *
 * @property \App\Models\Vehicle $vehicle
 * @property \App\Models\Vendor $vendor
 * @property User $creator
 * @property \Illuminate\Database\Eloquent\Collection $media_links
 * @property User $deleter
 * @property Collection $comments
 *
 * @package App\Models
 */
class Service extends Eloquent
{
    use SoftDeletes;
    public $timestamps = false;

    protected $casts = [
        'vehicle_id' => 'int',
        'odometer' => 'int',
        'vendor_id' => 'int',
        'labor_price' => 'int',
        'parts_price' => 'int',
        'tax' => 'int',
        'total' => 'int',
        'created_by' => 'int'
    ];

    protected $dates = [
        'serviced_at',
        'deleted_at'
    ];

    protected $fillable = [
        'serviced_at',
        'vehicle_id',
        'odometer',
        'performed_service_types',
        'performed_renewal_types',
        'resolved_issues',
        'vendor_id',
        'reference',
        'labor_price',
        'parts_price',
        'tax',
        'total',
        'tax_type',
        'created_by',
        'document_ids',
        'photo_ids',
    ];

    public function vehicle()
    {
        return $this->belongsTo(\App\Models\Vehicle::class);
    }

    public function vendor()
    {
        return $this->belongsTo(\App\Models\Vendor::class);
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function media_links()
    {
        return $this->hasMany(\App\Models\MediaLink::class);
    }


    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function comments()
    {

        return $this->hasMany(\App\Models\Comment::class, 'item_id')->where('type', 'service');

    }

    public function getPublicData($excludeFields = [], $deep = true)
    {
        $service = $this->toArray();
        unset(
            $service['vehicle_id'],
            $service['vendor_id'],
            $service['created_by'],
            $service['deleted_by'],
            $service['deleted_at'],
            $service['performed_service_types']
        );
        $service['vehicle'] = !empty($this->getAttribute('vehicle')) ? $this->getAttribute('vehicle')->getPublicData(['services'], false) : null;
        $service['vendor'] = !empty($this->getAttribute('vendor')) ? $this->getAttribute('vendor')->getPublicData([], false) : null;
        $service['creator'] = !empty($this->getAttribute('creator')) ? $this->getAttribute('creator')->getPublicData([], false) : null;
        $service['service_tasks'] = !empty($this->getAttribute('performed_service_types')) ? ServiceType::whereIn('id',
            explode(",", $this->getAttribute('performed_service_types')))->get() : null;
        $service['renewal_tasks'] = !empty($this->getAttribute('performed_renewal_types')) ? RenewalType::whereIn('id',
            explode(",", $this->getAttribute('performed_renewal_types')))->get() : null;
        $service['resolved_issues'] = [];
        $service['photo_ids'] = !empty($this->getAttribute('photo_ids')) ? json_decode($this->getAttribute('photo_ids'),
            true) : [];
        $service['document_ids'] = !empty($this->getAttribute('document_ids')) ? json_decode($this->getAttribute('document_ids'),
            true) : [];


        if (!empty($this->getAttribute('resolved_issues'))) {
            $issues = Issue::whereIn('id', explode(",", $this->getAttribute('resolved_issues')))->get();
            if ($issues) {
                foreach ($issues as $issue) {
                    $service['resolved_issues'][] = $issue->getPublicData([
                        'vehicle',
                        'reporter',
                        'assignee',
                        'comments'
                    ], false);
                }
            }

        }

        if ($this->getAttribute('photo_ids')) {
            $photos = Medium::whereIn('id', json_decode($this->getAttribute('photo_ids'), true))->get();
            if ($photos) {
                foreach ($photos as $photo) {
                    $service['photos'][] = [
                        'title' => $photo->title,
                        'description' => $photo->description,
                        'url' => $photo->getPublicPath('medium'),
                        'fileId' => $photo->id,
                        'type' => $photo->type
                    ];
                }
            }
        }


        $service['documents'] = [];
        if ($this->getAttribute('document_ids')) {
            $docs = Medium::whereIn('id', json_decode($this->getAttribute('document_ids'), true))->get();
            if ($docs) {
                foreach ($docs as $doc) {
                    $service['documents'][] = [
                        'title' => $doc->title,
                        'description' => $doc->description,
                        'url' => $doc->getPublicPath(),
                        'fileId' => $doc->id,
                        'type' => $doc->type
                    ];
                }
            }
        }

        $service['comments'] = [];
        if ($this->getAttribute('comments') && $deep) {
            foreach ($this->getAttribute('comments') as $comment) {
                $service['comments'][] = $comment->getPublicData([], false);

            }
        }

        return $service;
    }
}
