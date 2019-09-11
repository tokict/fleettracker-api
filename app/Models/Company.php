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
 * Class Company
 *
 * @property int $id
 * @property string $name
 * @property string $contact_phone
 * @property string $address
 * @property string $city
 * @property string $region
 * @property int $country_id
 * @property string $tax_id
 * @property int $contact_id
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property int $deleted_by
 * @property int $company_id
 * @property string $itrack_token
 *
 * @property \App\Models\Contact $contact
 * @property \App\Models\Country $country
 * @property Company $company
 * @property \Illuminate\Database\Eloquent\Collection $groups
 * @property \Illuminate\Database\Eloquent\Collection $users
 * @property \Illuminate\Database\Eloquent\Collection $vehicles
 * @property \Illuminate\Database\Eloquent\Collection $vendors
 * @property \Illuminate\Database\Eloquent\Collection $drivers
 * @property \Illuminate\Database\Eloquent\Collection $services
 * @property \Illuminate\Database\Eloquent\Collection $active_issues
 * @property \Illuminate\Database\Eloquent\Collection $active_vehicle_assignments
 * @property \Illuminate\Database\Eloquent\Collection $comments
 *
 *
 * @package App\Models
 */
class Company extends Eloquent
{
    use SoftDeletes;
    public $timestamps = false;

    protected $casts = [
        'country_id' => 'int',
        'contact_id' => 'int'
    ];

    protected $fillable = [
        'name',
        'contact_phone',
        'address',
        'city',
        'region',
        'country_id',
        'tax_id',
        'contact_id',
        'company_id',
        'itrack_token'

    ];

    public function contact()
    {
        return $this->belongsTo(\App\Models\Contact::class);
    }

    public function country()
    {
        return $this->belongsTo(\App\Models\Country::class);
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    public function groups()
    {
        return $this->hasMany(\App\Models\Group::class);
    }

    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }

    public function vehicles()
    {
        return $this->hasMany(\App\Models\Vehicle::class);
    }

    public function services()
    {
        return $this->hasMany(\App\Models\Service::class);
    }

    public function vendors()
    {
        return $this->hasMany(\App\Models\Vendor::class);
    }

    public function drivers()
    {
        return $this->hasMany(\App\Models\Contact::class)->where('driver', 1);
    }

    public function issues()
    {
        return $this->hasMany(\App\Models\Issue::class);
    }

    public function vehicle_assignments()
    {
        return $this->hasMany(\App\Models\Assignment::class)->where('type', 'vehicle');
    }


    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class);
    }


    public function getPublicData($excludeFields = [], $deep = true)
    {
        $company = $this->toArray();
        unset(
            $company['country_id'],
            $company['contact_id'],
            $company['itrack_token']

        );
        $company['country'] = !empty($this->getAttribute('country')) ? $this->getAttribute('country')->getPublicData([],
            false) : null;
        $company['contact'] = !empty($this->getAttribute('contact') && $deep) ? $this->getAttribute('contact')->getPublicData([],
            false) : null;
        $company['created_at'] = date("Y-m-d", strtotime($this->getAttribute('created_at')));
        $company['vehicles'] = !empty($this->getAttribute('vehicles') && $deep) ? $this->getAttribute('vehicles') : null;
        $company['active_issues'] = !empty($this->getAttribute('issues')->whereIn('status',
                ['feedback', 'in_progress', 'open']) && $deep)
            ? $this->getAttribute('issues')->whereIn('status', ['feedback', 'in_progress', 'open']) : null;
        $company['drivers'] = !empty($this->getAttribute('drivers') && $deep) ? $this->getAttribute('drivers') : null;
        if ($deep) {
            $company['active_vehicle_assignments'] = $this->getActiveVehicleAssignments();
        }


        return $company;
    }

    public function getActiveVehicleAssignments()
    {
        $ass = [];
        foreach ($this->getAttribute('vehicle_assignments') as $assignment) {
            if (!$assignment->ended_at) {
                $ass[] = $assignment->getPublicData();
            }
        }
        return $ass;
    }
}
