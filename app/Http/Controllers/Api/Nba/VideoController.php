<?php

namespace App\Http\Controllers\Api\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Input,Response,DB,Route;
use App\Models\Nba\Video;
use App\Models\Nba\VideoTag;
use Illuminate\Support\Facades\Redis;

class VideoController extends BaseController
{
	/**
	 * @Author    linyiyuan
	 * @DateTime  2018-04-17
	 * @获取视频列表
	 */
    public function getVideoList(Request $request)
    {   
        $redis_key = 'nba_video_list';//声明视频缓存键
        
        //先去缓存里面拿视频的换粗，如果有则返回    
        $redis = Redis::connection('ddz_web');        
        if ($data=$redis->hget($redis_key,$redis_key)) {
            $data = json_decode($data);
            return $this->successReturn(200,$data);
        }

        //从数据库拿数据
    	if (intval($tag_id = $request->tag_id)) {
    		$where[] = ['id','=',$tag_id];
    	}
    	$where[] = ['is_show','=','1'];

    	$videoTag = VideoTag::select('name','desc','id','sort')
    						->where($where)
    						->orderBy('sort','desc') 
    						->orderBy('created_at','desc') 
    						->get();
    	if ($videoTag->isEmpty()) {
    		return $this->successReturn(200,[]);
    	}
    	$data = [];
    	foreach ($videoTag as $key ) {
    		$list = [];
    		$list['name'] = base64_decode($key->name);
    		$list['desc'] = $key->desc;
    		$list['sort'] = $key->sort;
    		$list['id'] = $key->id;

    		$where = [
    			['is_show','=','1'],
    			['tag_id','=',$key->id],
    			['time','<=', $this->getNowTime()]
    		];

    		$video = Video::select('id','title','type','tag_id','cover','data','sort','created_at')
    					  ->where($where)
    					  ->orderBy('sort','desc')
    					  ->orderBy('created_at','desc')
    					  ->get();
    		$one = [];
    		if ($video->isEmpty()) {
    			$one = [];
    		}else{
    			foreach ($video as $key) {
	    			$columnList = [];
	    			$columnList['id'] = $key->id;
	    			$columnList['title'] = $key->title;
	    			$columnList['type'] = $key->type;
                    $columnList['data'] = $key->data;
	    			$columnList['tag_id'] = $key->tag_id;
	    			$columnList['cover'] = $this->getFullUrl($key->cover);
	    			$columnList['sort'] = $key->sort;
	    			$columnList['created_at'] = $key->created_at->format('Y-m-d');
	    		
	    			$one[] = $columnList;
    			}
    		}
    		$list['list'] = $one;
    		$data[] = $list;
    	}

        //加入缓存
        $redis = Redis::connection('ddz_web_m');
        Redis::hset($redis_key,$redis_key,json_encode($data));

    	return $this->successReturn(200,$data);
    }
}
