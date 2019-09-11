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
 * Class VehicleMaker
 * 
 * @property int $id
 * @property string $name
 * @property int $country_id
 * @property Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property int $deleted_by
 * 
 * @property \App\Models\Country $country
 * @property User $deleter
 * @property \Illuminate\Database\Eloquent\Collection $vehicles
 * @property \Illuminate\Database\Eloquent\Collection $vehicle_models
 *
 * @package App\Models
 */
class VehicleMaker extends Eloquent
{
	use SoftDeletes;
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	protected $casts = [
		'country_id' => 'int'
	];

	protected $fillable = [
		'name',
		'country_id'
	];

	public function country()
	{
		return $this->belongsTo(\App\Models\Country::class);
	}

	public function vehicles()
	{
		return $this->hasMany(\App\Models\Vehicle::class, 'brand');
	}

	public function vehicle_models()
	{
		return $this->hasMany(\App\Models\VehicleModel::class);
	}

	public function deleter()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

    public function getPublicData($excludeFields = [])
	{
		$maker = $this->toArray();
		unset(
            $maker['country_id']
		);
        $maker['country'] = !empty($this->getAttribute('country')) ? $this->getAttribute('country')->getPublicData() : null;
        $maker['models'] = !empty($this->getAttribute('vehicle_models')) ? $this->getAttribute('vehicle_models')->toArray() : null;
		return $maker;
	}
}
