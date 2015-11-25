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

    public function show()
    {
        return $this->belongsTo('App\Models\Show', 'seriesid');
    }

}
