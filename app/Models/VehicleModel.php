<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 19 Feb 2017 12:06:44 +0000.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Reliese\Database\Eloquent\Model as Eloquent;


/**
 * Class VehicleModel
 * 
 * @property int $id
 * @property string $name
 * @property int $vehicle_maker_id
 * @property Carbon $deleted_at
 * @property int $deleted_by
 * @property \Carbon\Carbon $created_at
 * 
 * @property \App\Models\VehicleMaker $vehicle_maker
 * @property User $deleter
 * @property \Illuminate\Database\Eloquent\Collection $vehicles
 *
 * @package App\Models
 */
class VehicleModel extends Eloquent
{
	use SoftDeletes;
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	protected $casts = [
		'vehicle_maker_id' => 'int'
	];

	protected $fillable = [
		'name',
		'vehicle_maker_id'
	];

	public function vehicle_maker()
	{
		return $this->belongsTo(\App\Models\VehicleMaker::class);
	}

	public function vehicles()
	{
		return $this->hasMany(\App\Models\Vehicle::class, 'model');
	}

	public function deleter()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

    public function getPublicData($excludeFields = [])
	{
		$maker = $this->toArray();
		unset(
			$maker['vehicle_maker_id']
		);
		$maker['vehicle_maker'] = !empty($this->getAttribute('vehicle_maker')) ? $this->getAttribute('vehicle_maker')->toArray() : null;

		return $maker;
	}
}
