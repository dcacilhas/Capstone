<?php

namespace App\Models;

use Codesleeve\Stapler\ORM\EloquentTrait;
use Codesleeve\Stapler\ORM\StaplerableInterface;
use Elasticquent\ElasticquentTrait;
use Eloquent;
use Fenos\Notifynder\Notifable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Eloquent implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, StaplerableInterface
{
    use Authenticatable, Authorizable, CanResetPassword, ElasticquentTrait, EloquentTrait, Notifable;

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
        'avatar',
        'notification_email',
        'profile_visibility',
        'list_visibility'
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

    /**
     * Specify ElasticSearch mapping properties.
     *
     * @var array
     */
    protected $mappingProperties = [
        'username' => [
            'type' => 'string',
            'analyzer' => 'standard'
        ]
    ];

    public function __construct(array $attributes = [])
    {
        // Define avatar attachment for Laravel Stapler
        $this->hasAttachedFile('avatar', [
            'styles' => [
                'large' => '256x256',
                'avatar' => '128x128',
                'thumb' => '64x64'
            ],
            'default_url' => 'assets/img/:style/avatar-placeholder.png',
            'default_style' => 'avatar'
        ]);

        parent::__construct($attributes);
    }

    /**
     * Get all episodes the user has watched.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function episodesWatched()
    {
        return $this->hasManyThrough('App\Models\ListEpisodesWatched', 'App\Models\Lists', 'user_id', 'list_id');
    }

    /**
     * Get the user's lists.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getList()
    {
        return $this->hasMany('App\Models\Lists');
    }

    /**
     * @return mixed
     */
    // TODO: Remove??
    public function getListWithSeries()
    {
        return $this->hasMany('App\Models\Lists')
            ->select('list.*', 'tvseries.SeriesName')
            ->join('tvseries', 'list.series_id', '=', 'tvseries.id')
            ->where('user_id', $this->id);
    }

    public function favourites()
    {
        return $this->hasMany('App\Models\Favourite');
    }

    public function isShowFavourited($seriesId)
    {
        return $this->favourites()->where('series_id', $seriesId)->exists();
    }

    public function favouritesWithSeries()
    {
        return $this->hasMany('App\Models\Favourite')
            ->join('tvseries', 'favourites.series_id', '=', 'tvseries.id')
            ->orderBy('sort_order', 'asc')
            ->get();
    }
}
