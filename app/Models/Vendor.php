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
 * Class Vendor
 * 
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $city
 * @property string $zip
 * @property string $region
 * @property int $country_id
 * @property string $contact_person_name
 * @property string $contact_person_email
 * @property string $contact_person_phone
 * @property int $company_id
 * @property \Carbon\Carbon $created_at
 * @property Carbon $deleted_at
 * @property int $deleted_by
 * 
 * @property \App\Models\Company $company
 * @property \App\Models\Country $country
 * @property User $deleter
 * @property \Illuminate\Database\Eloquent\Collection $comments
 * @property \Illuminate\Database\Eloquent\Collection $services
 *
 * @package App\Models
 */
class Vendor extends Eloquent
{
	use SoftDeletes;
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	protected $casts = [
		'country_id' => 'int',
		'company_id' => 'int'
	];

	protected $fillable = [
		'name',
		'phone',
		'address',
		'city',
		'zip',
		'region',
		'country_id',
		'contact_person_name',
		'contact_person_email',
		'contact_person_phone',
		'company_id'
	];

	public function company()
	{
		return $this->belongsTo(\App\Models\Company::class);
	}

	public function country()
	{
		return $this->belongsTo(\App\Models\Country::class);
	}

	public function services()
	{
		return $this->hasMany(\App\Models\Service::class);
	}


	public function deleter()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'item_id')->where('type', 'vendor')
            ->orderBy('created_at', 'desc');
    }

    public function getPublicData($excludeFields = [])
	{
		$vendor = $this->toArray();
		unset(
			$vendor['country_id'],
			$vendor['company_id'],
			$vendor['deleted_at'],
			$vendor['deleted_by']

		);
		$vendor['country'] = !empty($this->getAttribute('country')) ? $this->getAttribute('country')->getPublicData() : null;
		$vendor['company'] = !empty($this->getAttribute('company')) ? $this->getAttribute('company')->getPublicData([], false) : null;
		$vendor['comments'] = [];
		if ($this->getAttribute('comments')) {
			foreach ($this->getAttribute('comments') as $comment) {
				$vendor['comments'][] = $comment->getPublicData();

			}
		}
		return $vendor;
	}


}
