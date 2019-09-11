<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 19 Feb 2017 12:06:44 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ServiceType
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property int $multiple
 *
 *
 * @package App\Models
 */
class ServiceType extends Eloquent
{
    use SoftDeletes;
    public $timestamps = false;

    protected $casts = [
        'multiple' => 'int'
    ];

    protected $fillable = [
        'name',
        'multiple'
    ];



    /**
     * @return array
     */
    public function getPublicData($excludeFields = [])
    {
        $service = $this->toArray();
        return $service;
    }

}
