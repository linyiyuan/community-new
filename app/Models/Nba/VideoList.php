<?php

namespace App\Models\Nba;

use Illuminate\Database\Eloquent\Model;

class VideoList extends Model
{

   protected $table = 'nba_video_list';

   public function videos()
    {
        return $this->belongsToMany('App\Models\Nba\Video','nba_list_video');
    }
}
