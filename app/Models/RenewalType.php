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
 * @property \Carbon\Carbon $created_at
 * @property string $name
 *
 *
 * @property \Illuminate\Database\Eloquent\Collection $reminders
 *
 * @package App\Models
 */
class RenewalType extends Eloquent
{
    use SoftDeletes;
    public $timestamps = false;


    protected $fillable = [
        'name',

    ];


    /**
     * @return array
     */
    public function getPublicData($excludeFields = [])
    {
        $renewal = $this->toArray();
        return $renewal;
    }


}
