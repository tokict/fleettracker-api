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
 * Class Group
 * 
 * @property int $id
 * @property string $name
 * @property int $company_id
 * @property Carbon $deleted_at
 * @property int $deleted_by
 * @property \Carbon\Carbon $created_at
 *
 * @property \Illuminate\Database\Eloquent\Collection $vehicles
 * @property Company $company
 *
 * @package App\Models
 */
class Group extends Eloquent
{
	use SoftDeletes;
	public $timestamps = false;

	protected $casts = [
		'company_id' => 'int'
	];
	protected $fillable = [
		'name',
		'company_id'
	];



	public function vehicles()
	{
		return $this->hasMany(\App\Models\Vehicle::class);
	}

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

    public function getPublicData($excludeFields = [])
	{
		$group = $this->toArray();
		unset(
			$group['company_id']
		);
		return $group;
	}
}
