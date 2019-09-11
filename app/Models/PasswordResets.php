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
 * @property string $email
 * @property string $token
 * @property \Carbon\Carbon $created_at
 *
 *
 * @package App\Models
 */
class PasswordResets extends Eloquent
{

	public $timestamps = false;

	protected $fillable = [
		'email',
		'token',
		'created_at'
	];


}
