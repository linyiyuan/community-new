<?php

namespace App\Http\Controllers\Api\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Input,Response,DB,Route;
use App\Models\Nba\ContentTag;
use App\Models\Nba\Content;
use Illuminate\Support\Facades\Redis;

class ContentController extends BaseController
{
	/**
	 * @Author    linyiyuan
	 * @DateTime  2018-04-17
	 * @获取到图文列表
	 */
    public function getContentList(Request $request)
    {
        $redis_key = 'nba_content_list';//图文缓存键
        // 先从缓存里拿值
        $redis = Redis::connection('ddz_web');
        if ($data=$redis->hget($redis_key,$redis_key)) {
            $data = json_decode($data);
            return $this->successReturn(200,$data);
        }

    	$where = [];
    	if (intval($tag_id = $request->tag_id)) {
    		$where[] = ['id','=',$tag_id];
    	}
    	$where[] = ['is_show','=','1'];

    	$contentTag = ContentTag::select('id','name','desc','created_at','sort')
    							->where($where)
    							->orderBy('sort','desc')
    							->orderBy('created_at','desc')
    							->get();

    	$data = [];
    	foreach ($contentTag as $key ) {
    		$list = [];
    		$list['name'] = base64_decode($key->name);
    		$list['desc'] = $key->desc;
    		$list['sort'] = $key->sort;
    		$list['id'] = $key->id;
    		$list['created_at'] = $key->created_at->format('Y-m-d');

    		$where = [
    			['is_show','=','1'],
    			['tag_id','=',$key->id],
    			['time','<=', $this->getNowTime()]
    		];

    		$content = Content::select('id','title','tag_id','cover','sort','created_at','content')
    					  ->where($where)
    					  ->orderBy('sort','desc')
    					  ->orderBy('created_at','desc')
    					  ->get();

    		$one = [];
    		foreach ($content as $key) {
    			$contentList = [];
    			$contentList['id'] = $key->id;
    			$contentList['title'] = $key->title;
    			$contentList['content'] = $key->content;
    			$contentList['tag_id'] = $key->tag_id;
    			$contentList['cover'] = $this->getFullUrl($key->cover);
    			$contentList['sort'] = $key->sort;
    			$contentList['created_at'] = $key->created_at->format('Y-m-d');
    			$one[] = $contentList;
    		}

    		$list['list'] = $one;

    		$data[] = $list;
    	}

        //加入缓存
        $redis = Redis::connection('ddz_web_m');
        $redis->hset($redis_key,$redis_key,json_encode($data));//加入图文缓存
    	return $this->successReturn(200,$data);
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-04-17
     * @获取图文详情
     * @param     [integer]      $id [图文id]
     */
    public function getContentDetail($id)
    {
    	if (!intval($id)) {
    		return $this->errorReturn(500,'非法参数');
    	}

        //先从缓存了拿数据
        $redis_key = 'nba_content_detail_id:'.$id;//图文缓存键

        $redis = Redis::connection('ddz_web');
        if ($data = $redis->hget($redis_key,$redis_key)) {
            $data = json_decode($data);
            return $this->successReturn(200,$data);
        }

    	$where = [
    		['time','<=', $this->getNowTime()],
    		['is_show','=','1'],
            ['id','=',$id]
    	];
    	$contentDetail = Content::select('id','title','cover','tag_id','content','created_at')
    						->where($where)
    						->find($id);

    	if (is_null($contentDetail)) {
    		return $this->successReturn(200,[]);
    	}

    	$data['id'] = $contentDetail->id;
    	$data['title'] = $contentDetail->title;
    	$data['cover'] = $this->getFullUrl($contentDetail->cover);
    	$data['content'] = $this->getUeditorData($contentDetail->content);
    	$data['created_at'] = $contentDetail->created_at->format('Y-m-d');

        //加入缓存
        $redis = Redis::connection('ddz_web_m');
        $redis->hset($redis_key,$redis_key,json_encode($data));//加入缓存
        
     	return $this->successReturn(200,$data);
    }
}
