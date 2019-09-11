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
 * Class Subscription
 * 
 * @property int $id
 * @property int $contact_id
 * @property int $item_id
 * @property int $vehicle_id
 * @property string $type
 * @property \Carbon\Carbon $created_at
 * @property Carbon $deleted_at
 * @property int $deleted_by
 * 
 * @property \App\Models\Contact $contact
 * @property User $deleter
 * @property Vehicle $vehicle
 *
 * @package App\Models
 */
class Subscription extends Eloquent
{
	use SoftDeletes;
	public $timestamps = false;

	protected $casts = [
		'contact_id' => 'int',
		'item_id' => 'int',
		'vehicle_id' => 'int'
	];
	protected $dates = ['deleted_at'];
	protected $fillable = [
		'vehicle_id',
		'contact_id',
		'item_id',
		'type'
	];

	public function contact()
	{
		return $this->belongsTo(\App\Models\Contact::class);
	}

	public function deleter()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function vehicle()
	{
		return $this->belongsTo(\App\Models\Vehicle::class);
	}
}
