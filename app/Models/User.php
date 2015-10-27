<?php

namespace App\Models;

use Elasticquent\ElasticquentTrait;
use Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Eloquent implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, ElasticquentTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'about',
        'birthday',
        'location',
        'gender',
        'avatar_path',
        'notification_email',
        'profile_visibility',
        'list_visibility'
    ];

    protected $mappingProperties = [
        'username' => [
            'type' => 'string',
            'analyzer' => 'standard'
        ]
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'password_confirmation',
        'remember_token',
        'avatar_path',
        'notification_email',
        'profile_visibility',
        'list_visibility',
        'notifications_last_checked'
    ];

    public function getList() {
        return $this->hasMany('App\Models\Lists');
    }

    public function getListWithSeries() {
        return $this->hasMany('App\Models\Lists')
            ->select('list.*', 'tvseries.SeriesName')
            ->join('tvseries', 'list.series_id', '=', 'tvseries.id')
            ->where('user_id', $this->id);
    }
}
