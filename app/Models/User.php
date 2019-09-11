<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 19 Feb 2017 12:06:44 +0000.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * Class User
 *
 * @property int $id
 * @property string $email
 * @property string $username
 * @property string $password
 * @property int $contact_id
 * @property int $driver
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property string $status
 * @property int $super_admin
 * @property int $company_id
 * @property string $remember_token
 * @property Carbon $deleted_at
 * @property int $deleted_by
 *
 * @property \App\Models\Company $company
 * @property \App\Models\Contact $contact
 * @property User $deleter
 * @property \Illuminate\Database\Eloquent\Collection $comments
 * @property \Illuminate\Database\Eloquent\Collection $media
 * @property \Illuminate\Database\Eloquent\Collection $media_links
 * @property \Illuminate\Database\Eloquent\Collection $subscriptions
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    public $incrementing = false;
    public $timestamps = false;
    protected $casts = [
        'id' => 'int',
        'contact_id' => 'int',
        'driver' => 'int',
        'created_by' => 'int',
        'super_admin' => 'int',
        'company_id' => 'int'
    ];

    protected $hidden = [
        'password'
    ];
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'email',
        'username',
        'password',
        'contact_id',
        'driver',
        'created_by',
        'status',
        'super_admin',
        'company_id',
        'remember_token'
    ];

    public function company()
    {
        if (isset(\Auth::user()->company_id) && !empty($_SERVER['company']) && $_SERVER['company'] != 'null' && Auth::user()->super_admin) {

            //This is admin viewing some company
            return $this->belongsTo(\App\Models\Company::class)->where('id', \Auth::user()->company_id);
        } else {
            return $this->belongsTo(\App\Models\Company::class, 'company_id', 'id');

        }
    }

    public function contact()
    {
        return $this->belongsTo(\App\Models\Contact::class);
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class);
    }

    public function media()
    {
        return $this->hasMany(\App\Models\Medium::class, 'uploaded_by');
    }

    public function media_links()
    {
        return $this->hasMany(\App\Models\MediaLink::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Subscription::class);
    }

    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class);
    }


    public function getPublicData($excludeFields = [], $deep = true)
    {
        $user = $this->toArray();
        unset(
            $user['password'],
            $user['updated_at'],
            $user['created_by'],
            $user['password'],
            $user['company_id'],
            $user['contact_id'],
            $user['deleted_at'],
            $user['deleted_by'],
            $user['remember_token']
        );
        $user['company'] = !empty($this->getAttribute('company')) ? $this->getAttribute('company')->getPublicData([], false) : null;
        $user['contact'] = !empty($this->getAttribute('contact')) ? $this->getAttribute('contact')->getPublicData([], false) : null;
        $user['stats'] = $this->stats();

        return $user;
    }

    public function stats(){

        $arr = [];

        $arr['issues']['created'] = Issue::where('submitted_by', $this->getAttribute('id'))->whereIn('status', ['open', 'in_progress', 'feedback'])->count();


        $arr['vehicles']['created'] = Vehicle::where('created_by', $this->getAttribute('id'))->count();


        $arr['services']['created'] = Service::where('created_by', $this->getAttribute('id'))->count();


        $arr['service_reminders']['created'] = Reminder::where('created_by', $this->getAttribute('id'))->wherenotNull('service_type_id')->count();


        $arr['renewal_reminders']['created'] = Reminder::where('created_by', $this->getAttribute('id'))->wherenotNull('renewal_type_id')->count();



        return $arr;
    }
}
