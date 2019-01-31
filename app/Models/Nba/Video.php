<?php

namespace App\Models\Nba;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'nba_video';

    public function videoLists()
    {
        return $this->belongsToMany('App\Models\Nba\VideoList','nba_list_video');
    }

    public function getAll()
    {
        return $this->hasMany('App\Models\Nba\VideoList');
    }
}
