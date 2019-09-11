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
 * Class Comment
 *
 * @property int $user_id
 * @property string $token
 * @property int $contact_id
 * @property \Carbon\Carbon $created_at
 * @property int $activated
 *
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class UserActivation extends Eloquent
{

    protected $table = 'user_activations';
    public $timestamps = false;

    protected $casts = [
        'user_id' => 'int',
        'activated' => 'int'
    ];
    protected $dates = [
        'created_at'
    ];

    protected $fillable = [
        'user_id',
        'token',
        'created_at',
        'activated'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
