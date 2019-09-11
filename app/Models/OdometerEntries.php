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
 * Class OdometerEntries
 *
 * @property int $id
 * @property int $vehicle_id
 * @property string $date
 * @property int $odo_end
 * @property int $deleted_by
 * @property int $updated_by
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @property \App\Models\User $updater
 * @property \App\Models\User $creator
 * @property \App\Models\User $deleter
 *
 *
 * @package App\Models
 */
class OdometerEntries extends Eloquent
{
    use SoftDeletes;
    public $timestamps = false;


    protected $casts = [
        'vehicle_id' => 'int',
        'odo_end' => 'int',
        'deleted_by' => 'int',
        'updated_by' => 'int',
        'created_by' => 'int'
    ];
    protected $dates = [
        'deleted_at',
        'created_at',
        'date',
        'updated_at'
    ];

    protected $fillable = [
        'vehicle_id',
        'date',
        'odo_end',
    ];

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }


    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by');
    }

    public function vehicle()
    {
        return $this->belongsTo(\App\Models\Vehicle::class, 'vehicle_id');
    }

}
