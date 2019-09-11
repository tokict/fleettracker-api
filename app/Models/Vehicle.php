<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 19 Feb 2017 12:06:44 +0000.
 */

namespace App\Models;

use Carbon\Carbon;
use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Vehicle
 *
 * @property int $id
 * @property string $name
 * @property string $vin
 * @property string $type
 * @property int $year
 * @property int $maker_id
 * @property int $model_id
 * @property string $plate
 * @property string $photo_ids
 * @property string $document_id's
 * @property string $status
 * @property int $group_id
 * @property int $operator_id
 * @property string $ownership
 * @property string $color
 * @property string $body
 * @property int $msrp
 * @property int $length
 * @property int $bed_length
 * @property int $curb_weight
 * @property int $max_payload
 * @property string $cargo_volume
 * @property string $epa_city
 * @property string $epa_highway
 * @property int $epa_combined
 * @property string $drive_type
 * @property string $front_tire_type
 * @property string $rear_tire_type
 * @property string $fuel_type
 * @property string $fuel_tank_1_capacity
 * @property string $fuel_tank_2_capacity
 * @property string $oil_capacity
 * @property int $company_id
 * @property \Carbon\Carbon $created_at
 * @property Carbon $deleted_at
 * @property int $deleted_by
 * @property int $itrack_id
 *
 * @property \App\Models\Company $company
 * @property \App\Models\Contact $contact
 * @property \App\Models\Group $group
 * @property \App\Models\VehicleMaker $maker
 * @property \App\Models\VehicleModel $model
 * @property User $deleter
 * @property \Illuminate\Database\Eloquent\Collection $issues
 * * @property \Illuminate\Database\Eloquent\Collection $active_issues
 * @property \Illuminate\Database\Eloquent\Collection $services
 * @property \Illuminate\Database\Eloquent\Collection $media_links
 * @property \Illuminate\Database\Eloquent\Collection $reminders
 * @property \Illuminate\Database\Eloquent\Collection $assignments
 * @property \Illuminate\Database\Eloquent\Collection $documents
 * @property \Illuminate\Database\Eloquent\Collection $vehicles
 * @property \Illuminate\Database\Eloquent\Collection $odometer_entries
 * @property \Illuminate\Database\Eloquent\Collection $subscriptions
 *
 * @package App\Models
 */
class Vehicle extends Eloquent
{

    use SoftDeletes;
    public $timestamps = false;

    protected $casts = [
        'group_id' => 'int',
        'operator_id' => 'int',
        'msrp' => 'int',
        'length' => 'int',
        'maker_id' => 'int',
        'model_id' => 'int',
        'bed_length' => 'int',
        'curb_weight' => 'int',
        'max_payload' => 'int',
        'cargo_volume' => 'int',
        'company_id' => 'int',
        'itrack_id' => 'int'
    ];


    protected $fillable = [
        'name',
        'vin',
        'type',
        'year',
        'maker_id',
        'model_id',
        'plate',
        'photo_id',
        'status',
        'group_id',
        'operator_id',
        'ownership',
        'color',
        'body',
        'msrp',
        'length',
        'bed_length',
        'curb_weight',
        'max_payload',
        'cargo_volume',
        'epa_city',
        'epa_highway',
        'epa_combined',
        'drive_type',
        'front_tire_type',
        'rear_tire_type',
        'fuel_type',
        'fuel_tank_1_capacity',
        'fuel_tank_2_capacity',
        'oil_capacity',
        'company_id',
        'photo_ids',
        'document_ids',
        'itrack_id'
    ];


    protected $dates = ['deleted_at'];

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    public function contact()
    {
        return $this->belongsTo(\App\Models\Contact::class, 'operator_id');
    }

    public function group()
    {
        return $this->belongsTo(\App\Models\Group::class);
    }

    public function issues()
    {
        return $this->hasMany(\App\Models\Issue::class);
    }

    public function active_issues()
    {
        return $this->hasMany(\App\Models\Issue::class)->where('status', 'open');
    }

    public function services()
    {
        return $this->hasMany(\App\Models\Service::class);
    }

    public function media_links()
    {
        return $this->hasMany(\App\Models\MediaLink::class);
    }

    public function maker()
    {
        return $this->belongsTo(\App\Models\VehicleMaker::class, 'maker_id');
    }

    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function model()
    {
        return $this->belongsTo(\App\Models\VehicleModel::class, 'model_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Subscription::class, 'vehicle_id');
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'item_id')->where('type', 'vehicle')
            ->orderBy('created_at', 'desc');
    }

    public function assignments()
    {
        return $this->hasMany(\App\Models\Assignment::class, 'item_id')->where('type', 'vehicle');
    }

    public function odometer_entries()
    {
        return $this->hasMany(\App\Models\OdometerEntries::class)->orderBy('created_at');
    }

    public function renewals_history()
    {
        return $this->hasMany(\App\Models\Service::class)->whereNotNull('performed_renewal_types');

    }

    public function getPublicData($excludeFields = [], $deep = true)
    {

        $vehicle = $this->toArray();
        unset(
            $vehicle['group_id'],
            $vehicle['maker_id'],
            $vehicle['model_id'],
            $vehicle['deleted_at'],
            $vehicle['deleted_by'],
            $vehicle['operator_id'],
            $vehicle['company_id'],
            $vehicle['photo_ids'],
            $vehicle['document_ids']
        );
        $vehicle['group'] = !empty($this->getAttribute('group')) ? $this->getAttribute('group')->getPublicData() : null;
        $vehicle['maker'] = !empty($this->getAttribute('maker')) ? $this->getAttribute('maker')->getPublicData() : null;
        $vehicle['model'] = !empty($this->getAttribute('model')) ? $this->getAttribute('model')->getPublicData() : null;
        $vehicle['company'] = !empty($this->getAttribute('company') && $deep) ? $this->getAttribute('company')->getPublicData([], false) : null;
        $vehicle['operator'] = !empty($this->getAttribute('contact') && $deep) ? $this->getAttribute('contact')->getPublicData([], false) : null;
        $vehicle['document_count'] = !empty($this->documents()) ? $this->documents()->count() : 0;
        $vehicle['service_count'] = !empty($this->getAttribute('services')) ? $this->getAttribute('services')->count() : 0;
        $vehicle['issue_count'] = !empty($this->getAttribute('issues')) ? $this->getAttribute('issues')->count() : 0;
        $vehicle['assignment_count'] = !empty($this->getAttribute('assignments')) ? $this->getAttribute('assignments')->count() : 0;
        $vehicle['odometer'] = $this->getOdometerState();
        $vehicle['stats'] = $deep?$this->stats():null;
        //get renewal reminders data
        if($deep) {
            $vehicle['reminder_alerts']['renewal'] = Reminder::getAlerts('renewal', \Auth::user(),
                $this->getAttribute('id'));

            //get service reminders data
            $vehicle['reminder_alerts']['service'] = Reminder::getAlerts('service', \Auth::user(),
                $this->getAttribute('id'));
        }


        $vehicle['photos'] = [];
        if ($this->getAttribute('photo_ids')) {
            $ids = json_decode($this->getAttribute('photo_ids'), true) ? json_decode($this->getAttribute('photo_ids'),
                true) : [];
            $photos = Medium::whereIn('id', $ids)->get();
            if ($photos) {
                foreach ($photos as $photo) {
                    $vehicle['photos'][] = [
                        'title' => $photo->title,
                        'description' => $photo->description,
                        'url' => $photo->getPublicPath('medium')
                    ];
                }
            }
        }


        $vehicle['documents'] = [];
        $docs = $this->documents();
        if ($docs && $deep) {
            foreach ($docs as $doc) {
                $vehicle['documents'][] = [
                    'title' => $doc->title,
                    'description' => $doc->description,
                    'url' => $doc->getPublicPath()
                ];
            }
        }


        $vehicle['comments'] = [];
        if ($this->getAttribute('comments')&& $deep) {
            foreach ($this->getAttribute('comments') as $comment) {
                $vehicle['comments'][] = $comment->getPublicData();

            }
        }

        $vehicle['assignments'] = [];
        if ($this->getAttribute('assignments')&& $deep) {
            foreach ($this->getAttribute('assignments') as $assignment) {
                $vehicle['assignments'][] = [
                    'id' => $assignment->id,
                    'contact' => $assignment->assignee->getPublicData([], false),
                    'started_at' => $assignment->started_at ? $assignment->started_at->format("Y-m-d H:i:s") : null,
                    'ended_at' => $assignment->ended_at ? $assignment->ended_at->format("Y-m-d H:i:s") : null,
                    'odo_start' => $assignment->odo_start,
                    'odo_end' => $assignment->odo_end
                ];

            }
        }

        $vehicle['last_services'] = [];
        $vehicle['last_renewals'] = [];
        if ($this->getAttribute('services') && !in_array('services', $excludeFields)&& $deep) {
            foreach ($this->getAttribute('services') as $service) {

                if ($service['performed_service_types']) {
                    foreach (explode(',', $service['performed_service_types']) as $item) {
                        $name = ServiceType::find($item);
                        $vehicle['last_services'][$name->name] = [
                            'service_id' => $service->id,
                            'odometer' => $service->odometer,
                            'serviced_at' => $service->serviced_at,
                            'vendor' => $service->vendor_id ? Vendor::find($service->vendor_id)->getPublicData([], false) : null,
                        ];
                    }

                }


                if ($service['performed_renewal_types']) {
                    foreach (explode(',', $service['performed_renewal_types']) as $item) {
                        $name = RenewalType::find($item);
                        if($name) {
                            $vehicle['last_renewals'][$name->name] = [
                                'service_id' => $service->id,
                                'odometer' => $service->odometer,
                                'serviced_at' => $service->serviced_at,
                                'vendor' => $service->vendor_id ? Vendor::find($service->vendor_id)->getPublicData([], false) : null,
                            ];
                        }
                    }

                }

            }
        }

        $vehicle['reminders'] = [];
        if ($deep && $this->reminders()) {
            foreach ($this->reminders() as $reminder) {
                $vehicle['reminders'][] = [
                    'service_type' => $reminder->service_type ? $reminder->service_type->name : null,
                    'odometer_interval' => $reminder->odometer_interval,
                    'time_interval' => $reminder->time_interval,
                    'odometer_threshold' => $reminder->odometer_threshold,
                    'time_threshold' => $reminder->time_threshold,
                    'time_interval_unit' => $reminder->time_interval_unit,
                    'email' => $reminder->email,
                    'renewal_type' => $reminder->renewal_type ? $reminder->renewal_type->name : null,
                    'due_date' => $reminder->due_date,
                    'sms' => $reminder->sms,
                    'created_by' => $reminder->creator,
                ];

            }
        }

        $vehicle['odometer_entries'] = [];
        if ($this->getAttribute('odometer_entries')&& $deep) {
            foreach ($this->getAttribute('odometer_entries') as $entry) {
                $vehicle['odometer_entries'][] = [
                    'date' => $entry->date,
                    'odo_end' => $entry->odo_end,
                    'created_at' => $entry->created_at,
                    'updated_at' => $entry->updated_at
                ];

            }
        }

        return $vehicle;
    }

    /**
     * Get vehicles documents
     * @return mixed
     */
    public function documents()
    {
        $ids = json_decode($this->getAttribute('document_ids'), true) ?: [];
        $issueDocs = Issue::where('vehicle_id', $this->getAttribute('id'))->get();
        $serviceDocs = Service::where('vehicle_id', $this->getAttribute('id'))->get();
        if ($issueDocs) {
            foreach ($issueDocs as $item) {
                $d = json_decode($item->document_ids, true) ? json_decode($item->document_ids, true) : [];
                $ids = array_merge($ids, $d);
            }
        }


        if ($serviceDocs) {
            foreach ($serviceDocs as $item) {
                $d = json_decode($item->document_ids, true) ? json_decode($item->document_ids, true) : [];
                $ids = array_merge($ids, $d);
            }
        }


        if ($ids) {
            return \App\Models\Medium::whereIn('id', $ids)->where('type', 'document')->get();
        }

    }

    /**
     * Get reminders for a vehicle
     * @return \Illuminate\Support\Collection
     */
    public function reminders()
    {
        //Get subscriptions
        $subs = Subscription::where('vehicle_id', $this->getAttribute('id'))->whereIn('type', ['renewal', 'service'])->get();
        $subIds = [];
        foreach ($subs as $sub) {
            if (!in_array($sub->item_id, $subIds)) {
                $subIds[] = $sub->item_id;
            }
        }


        return \App\Models\Reminder::whereIn('id', $subIds)->get();
    }

    public function getOdometerState()
    {
        return !empty($this->odometer_entries()->get()->last()) ? $this->odometer_entries()->get()->last()->odo_end : null;
    }


    /**
     * Get vehicle usage and running stats
     * @return array
     */
    public function stats()
    {
        $ret = [];

        if (!$this->odometer_entries()->count()) {
            return false;
        }
        $odo_first = $this->odometer_entries()->get()->first()->odo_end;
        $odo_last = $this->odometer_entries()->get()->last()->odo_end;
        $first_date = $this->odometer_entries()->get()->first()->date;
        $last_date = $this->odometer_entries()->get()->last()->date;
        $nr_days = (int)floor((strtotime($last_date) - strtotime($first_date)) / (60 * 60 * 24));


        $total_km = $odo_last - $odo_first;

        //How many hundreds of km * litres per 100 * cost of litre
        $ret['costs']['fuel'] = ($total_km && $nr_days) ? number_format(($total_km / 100) * $this->getAttribute('epa_combined') * 2.04,
            0) : 0;
        $ret['costs']['service'] = number_format($this->services()->sum('total'));
        $ret['usage']['km_per_day'] = ($total_km && $nr_days) ? number_format($total_km / $nr_days, 0) : 0;
        $ret['usage']['graph_data'] = $this->getKmPerDay();


        return $ret;
    }


    public function getKmPerDay()
    {
        $entries = $this->odometer_entries()->get();
        $entriesArr = $entries->toArray();
        $days = [];


        foreach ($entries as $index => $entry) {
            //skip first entry because we dont have enough data to find traversed km
            if ($index == 0) {
                continue;
            }

            $last_odo = isset($entriesArr[$index -1])?$entriesArr[$index -1]['odo_end']:null;

            $days[$entry->date->format("Y-m-d")][] = ['start' => $last_odo?$last_odo:$entry->odo_end, 'end' => $entry->odo_end];
        }

        $dayValues = [];

        foreach ($days as $day => $values) {
            $data = array_reverse($values);
            $dayValues[$day] = $data[count($data) - 1]['end'] - $data[0]['start'];

        }

        return $dayValues;
    }
}
