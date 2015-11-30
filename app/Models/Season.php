<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tvseasons';

    /**
     * A season belongs to a show.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function show()
    {
        return $this->belongsTo('App\Models\Show', 'seriesid');
    }

    /**
     * A season has many episodes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function episodes()
    {
        return $this->hasMany('App\Models\Episode', 'seasonid');
    }

    /**
     * Query of seasons without specials.
     *
     * @param $query
     * @return mixed
     */
    public function scopeNoSpecials($query)
    {
        return $query->where('season', '<>', 0);
    }

}
