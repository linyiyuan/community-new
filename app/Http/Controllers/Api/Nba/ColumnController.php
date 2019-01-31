<?php

namespace App\Http\Controllers\Api\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Api\BaseController;
use App\Models\Nba\Column;
use App\Models\Nba\Video;
use App\Models\Nba\Manga;
use App\Models\Nba\MangaTag;
use App\Models\Nba\VideoTag;
use App\Models\Nba\VideoList;
use DB;


/**
 * @Author    linyiyuan
 * @DateTime  2018-04-16
 * @处理首页栏目的数据
 */
class ColumnController extends BaseController
{
    /**
     * @Author    linyiyuan
     * @DateTime  2018-04-17
     * @获取到首页栏目相关信息
     */
    public function getColumnList()
    {   
        //先从缓存中获取数据，如果没用再从数据库获取
        $redis_key = 'nba_column_list';
        $redis = Redis::connection('ddz_web');

        if ($list = $redis->hget($redis_key,$redis_key)) {
           $list = json_decode($list,true);
           return $list;
        }

    	$column = Column::select('column_name','type','data_id')
    					->orderBy('sort','asc')
    					->orderBy('created_at','desc')
    					->get(); 
    	if ($column->isEmpty()) {
    		 return [];
    	}

    	$data = [];

    	foreach ($column as $key) {
    		$list['column_name'] = base64_decode($key->column_name);
    		$list['type'] = $key->type;
    		$list['data_id'] = $key->data_id;

    		if ($key->type == 1) {
                if (VideoList::where('id',$key->data_id)->value('is_show') === 0) {
                    //判断视频集合是否显示
                    $list = [];
                }else{
                    //判断如果是视频集合类型
                     $videoIds = DB::table('nba_list_video')
                                    ->where('video_list_id',$key->data_id)
                                    ->select('video_id')
                                    ->get();

                     $videoIds = array_column($this->toArray($videoIds), 'video_id');
                     $where = [['time','<=',$this->getNowTime()],['is_show','=','1']];

                     $videoListContent =  Video::whereIn('id',$videoIds)
                                                ->where($where)
                                                ->orderBy('sort','desc')
                                                ->orderBy('created_at','desc')
                                                ->limit(4)
                                                ->get();

                    if ($videoListContent->isEmpty()) {
                        $list = [];
                    }else{
                        $one = [];
                        foreach ($videoListContent as $key) {
                            $columnList = [];
                            $videoTag  = $this->getVideoTag();
                            $columnList['id'] = $key->id;
                                  $columnList['title'] = $key->title;
                            $columnList['tag_name'] = $videoTag[$key->tag_id];
                            $columnList['cover'] = $this->getFullUrl($key->cover);
                            $columnList['data'] = $key->data;
                            $columnList['type'] = $key->type;
                            $columnList['time'] = $key->time;
                            $columnList['sort'] = $key->sort;

                            $one[] = $columnList;
                        } 
                    }
                    
                  $list['list'] = $one;
                }

                
    		}else if($key->type == 2){
                //判断如果是视频标签类型
                $tag_id = $key->data_id;//拿到视频标签id

                if (VideoTag::where('id',$tag_id)->value('is_show') === 0) {
                    //判断视频标签是否显示
                    $list = [];
                }else{
                    $where = [['time','<=',$this->getNowTime()],['tag_id','=',$tag_id],['is_show','=','1']];

                    $videoList = Video::where($where)
                                      ->orderBy('sort','desc')
                                      ->orderBy('created_at','desc')
                                      ->limit(4)
                                      ->get();
                    if ($videoList->isEmpty()) {
                        $list = [];
                    }else{
                        $one = [];
                            foreach ($videoList as $key) {
                                $columnList = [];
                                $videoTag  = $this->getVideoTag();
                                $columnList['id'] = $key->id;
                                $columnList['title'] = $key->title;
                                $columnList['tag_name'] = $videoTag[$key->tag_id];
                                $columnList['tag_id'] = $key->tag_id;
                                $columnList['cover'] = $this->getFullUrl($key->cover);
                                $columnList['data'] = $key->data;
                                $columnList['time'] = $key->time;
                                $columnList['type'] = $key->type;
                                $columnList['sort'] = $key->sort;

                                $one[] = $columnList;
                            }
                         $list['list'] = $one;
                    }
                }

            }else if($key->type == 3){
                //判断如果是漫画标签类型
                if (empty($tag_id = $key->data_id)) {
                    $list['list'] = [];//判断是否漫画标签是否不为空
                }
                if (MangaTag::where('id',$tag_id)->value('is_show') === 0) {
                    // 判断漫画标签是否显示
                    $list = [];
                }else{
                    $where = [
                                ['time','<=',$this->getNowTime()],
                                ['tag_id','=',$tag_id],
                                ['is_show','=','1']
                             ];

                    $mangaList = Manga::where($where)
                                     ->orderBy('sort','desc')
                                     ->orderBy('created_at','desc')
                                     ->limit(4)
                                     ->get();
                    if ($mangaList->isEmpty()) {
                        $list = [];
                    }else{
                        $one = [];
                        foreach ($mangaList as $key) {
                                $columnList = [];
                                $columnList['id'] = $key->id;
                                $columnList['title'] = $key->title;
                                $columnList['tag_id'] = $key->tag_id;
                                $columnList['cover'] = $this->getFullUrl($key->cover);
                                $columnList['time'] = $key->time;
                                $columnList['sort'] = $key->sort;

                                $one[] = $columnList;
                            }

                            $list['list'] = $one;
                        } 
                    }
                    
                }
    		$data[] = $list;
    	}

        //加入缓存
        $redis = Redis::connection('ddz_web_m');
        $redis->hset($redis_key,$redis_key,json_encode($data));
    	return $data;
    }


     /**
     * @Author    linyiyuan
     * @DateTime  2018-04-09
     * @copyright 获取视频标签
     */
    public function getVideoTag()
    {
        $videoTag = VideoTag::select('id','name')->get();

        $videoTag = json_decode(json_encode($videoTag),true);

        $videoTag = array_column($videoTag, 'name','id');

        $videoTag = array_map(function($val){ return base64_decode($val);},$videoTag );

        return $videoTag;
    }

}
