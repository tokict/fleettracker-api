<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 19 Feb 2017 12:06:44 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Reliese\Database\Eloquent\Model as Eloquent;


/**
 * Class Notification
 *
 * @property int $id
 * @property string $type
 * @property \Carbon\Carbon $sent_at
 * @property string $text
 * @property int $item_id
 * @property int $user_id
 * @property int $contact_id
 * @property int $vehicle_id
 * @property string $subtype
 * @property int $by_email
 * @property \Carbon\Carbon $created_at
 * @property int $by_sms
 *
 * @property User $user
 * @property Contact $contact
 *
 * @package App\Models
 */
class Notification extends Eloquent
{

    public $timestamps = false;

    protected $casts = [
        'item_id' => 'int',
        'by_email' => 'int',
        'by_sms' => 'int',
        'user_id' => 'int',
        'contact_id'=> 'int',
        'vehicle_id'=> 'int'

    ];

    protected $dates = [
        'sent_at'
    ];

    protected $fillable = [
        'type',
        'sent_at',
        'text',
        'item_id',
        'subtype',
        'by_email',
        'by_sms',
        'user_id',
        'contact_id',
        'vehicle_id'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function contact()
    {
        return $this->belongsTo(\App\Models\Contact::class);
    }
}
