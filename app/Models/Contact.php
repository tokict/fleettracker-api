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
 * Class Contact
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property \Carbon\Carbon $birth_date
 * @property int $group_id
 * @property string $email
 * @property string $mobile_phone
 * @property string $home_phone
 * @property string $work_phone
 * @property string $other_phone
 * @property string $address
 * @property string $address_2
 * @property string $city
 * @property string $region
 * @property string $zip
 * @property int $country_id
 * @property int $user_id
 * @property string $employee_number
 * @property string $job_title
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon $leave_date
 * @property int $driver
 * @property string $driver_license_number
 * @property string $driver_license_class
 * @property string $driver_license_region
 * @property int $hourly_rate
 * @property \Carbon\Carbon $created_at
 * @property Carbon $deleted_at
 * @property int $deleted_by
 * @property int $itrack_id
 *
 * @property Group $group
 * @property User $user
 * @property Medium $image
 *
 * @property \Illuminate\Database\Eloquent\Collection $companies
 * @property \Illuminate\Database\Eloquent\Collection $issues
 * @property \Illuminate\Database\Eloquent\Collection $reported_issues
 * @property \Illuminate\Database\Eloquent\Collection $vehicles
 * * @property \Illuminate\Database\Eloquent\Collection $subscriptions
 * @property \Illuminate\Database\Eloquent\Collection $vehicle_assignments
 *
 * @package App\Models
 */
class Contact extends Eloquent
{
    use SoftDeletes;
    public $timestamps = false;
    protected $casts = [
        'group_id' => 'int',
        'country_id' => 'int',
        'driver' => 'int',
        'hourly_rate' => 'int',
        'user_id' => 'int',
        'itrack_id' => 'int',

    ];

    protected $dates = [
        'date_of_birth',
        'start_date',
        'leave_date'
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'group_id',
        'email',
        'mobile_phone',
        'home_phone',
        'work_phone',
        'other_phone',
        'address',
        'address_2',
        'city',
        'region',
        'zip',
        'country_id',
        'employee_number',
        'job_title',
        'start_date',
        'leave_date',
        'driver',
        'driver_license_number',
        'driver_license_class',
        'driver_license_region',
        'hourly_rate',
        'user_id',
        'itrack_id'
    ];


    public function companies()
    {
        return $this->hasMany(\App\Models\Company::class);
    }

    public function issues()
    {
        return $this->hasMany(\App\Models\Issue::class, 'assigned_to');
    }
    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Subscription::class);
    }

    public function reported_issues()
    {
        return $this->hasMany(\App\Models\Issue::class, 'reported_by');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function group()
    {
        return $this->belongsTo(\App\Models\Group::class);
    }

    public function image()
    {
        return $this->belongsTo(\App\Models\Medium::class);
    }

    public function country()
    {
        return $this->belongsTo(\App\Models\Country::class);
    }

    public function vehicles()
    {
        return $this->hasMany(\App\Models\Vehicle::class, 'operator_id');
    }

    public function assignments()
    {
        return $this->hasMany(\App\Models\Assignment::class, 'contact_id');
    }

    public function getPublicData($excludeFields = [], $deep = true)
    {
        $contact = $this->toArray();
        unset(
            $contact['group_id'],
            $contact['country_id'],
            $contact['image_id']


        );
        $contact['group'] = !empty($this->getAttribute('group')) ? $this->getAttribute('group')->getPublicData() : null;
        $contact['country'] = !empty($this->getAttribute('country')) ? $this->getAttribute('country')->getPublicData() : null;
        $contact['assigned_vehicles'] = [];
        $contact['assigned_issues'] = [];
        $contact['photos']=[];

        if (!empty($this->getAttribute('assignments')&& $deep)) {
            foreach ($this->getAttribute('assignments') as $assignment) {
                $comments = [];
                foreach ($assignment->comments as $comment) {
                    $comment->getPublicData([], false);
                }
                if($assignment->type == 'vehicle') {

                    $photos = [];
                    if (isset($assignment->item->photo_ids)){

                        $ids = json_decode($assignment->item->photo_ids, true) ? json_decode($assignment->item->photo_ids,
                            true) : [];
                        $ps =  Medium::whereIn('id', $ids)->get();
                        foreach ($ps as $p){
                            $x = $p->getPublicData();
                            $photos[] = $x;
                        }
                    }

                    $contact['assigned_vehicles'][] = [
                        'id' => $assignment->item->id,
                        'name' => $assignment->item->name,
                        'photos' => $photos,
                        'start_date' => $assignment->started_at,
                        'end_date' => $assignment->ended_at,
                        'odo_start' => $assignment->odo_start,
                        'item' => $assignment->item,
                        'odo_end' => $assignment->odo_end,
                        'comments' => $comments
                    ];
                }

            }
        }

        foreach ($this->getAttribute('issues') as $issue){

                $contact['assigned_issues'][] = $issue->getPublicData(['submitter', 'assignee', 'reporter'], false);

        }
        $contact['pic'] = !empty($this->getAttribute('image')) ? $this->getAttribute('image')->getPath('small') : null;
        return $contact;
    }

}
