<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 19 Feb 2017 12:06:44 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Country
 * 
 * @property int $id
 * @property string $name
 * @property int $eu
 * @property int $priority
 * @property string $code
 * @property $string $deleted_at
 * @property \Carbon\Carbon $created_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $companies
 * @property \Illuminate\Database\Eloquent\Collection $vehicle_makers
 * @property \Illuminate\Database\Eloquent\Collection $vendors
 *
 * @package App\Models
 */
class Country extends Eloquent
{
	use SoftDeletes;
	public $timestamps = false;

	protected $casts = [
		'eu' => 'int',
		'priority' => 'int'
	];

	protected $fillable = [
		'name',
		'eu',
		'priority'
	];

	public function companies()
	{
		return $this->hasMany(\App\Models\Company::class);
	}

	public function vehicle_makers()
	{
		return $this->hasMany(\App\Models\VehicleMaker::class);
	}

	public function vendors()
	{
		return $this->hasMany(\App\Models\Vendor::class);
	}

    public function getPublicData($excludeFields = [])
	{
		$country = $this->toArray();
		return $country;
	}
}
