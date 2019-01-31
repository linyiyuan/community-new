<?php

namespace App\Http\Controllers\Api\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Nba\ColumnController;
use App\Models\Nba\Content;
use App\Models\Nba\Calendar;
use App\Models\Nba\Carousel;
use App\Models\Nba\Column;
use App\Models\Nba\ContentTag;
use Input,Response,DB,Route;
use Illuminate\Support\Facades\Redis;

/**
 * @Author    linyiyuan
 * @DateTime  2018-04-16
 * @拿到首页的数据
 */
class HomeController extends BaseController
{
	/**
	 * @Author    linyiyuan
	 * @DateTime  2018-04-16
	 * @拿到首页的数据
	 */
    public function getHomeList()
    {
    	//首页轮播图
    	$list['carousel'] = $this->getCarouselList();

    	//首页老黄历
    	$list['calendar'] = $this->getCalendarList();

    	//首页图文
    	$list['content'] = $this->getContentList();

    	//首页栏目
        $column = new ColumnController();
    	$list['column'] = $column->getColumnList();

    	return $this->successReturn(200,$list);
    }


    /**
     * @Author    linyiyuan
     * @DateTime  2018-04-16
     * @轮播图列表
     */
    protected function getCarouselList()
    {
        

        //先从redis缓存拿数据,如果有就返回
        $redis_key  = 'nba_carousel_list';//声明轮播图缓存的键
        $redis = Redis::connection('ddz_web');
        if ($data=$redis->hget($redis_key,$redis_key)) {
            $data = json_decode($data);
            return $data;
        }

    	$where = [
    		['is_show','=','1']
    	];

    	$data = [];
        $carousel = Carousel::where($where)
        					->orderBy('sort','asc')
        					->orderBy('created_at','desc')
        					->limit(5)
        		            ->get();

        if ($carousel->isEmpty()) {
        	return [];
        }

        foreach ($carousel as $key) {
        	$list = [];
        	$list['title'] = $key->title;
        	$list['url'] = $key->url;
        	$list['type'] = $key->type;
        	$list['img'] = $this->getFullUrl($key->img);
        	$list['is_show'] = $key->is_show;
        	$list['third_id'] = $key->third_id;
        	$list['pic_type'] = $key->pic_type;
        	$list['sort'] = $key->sort;
        	$data[] = $list;
        }

        //加入缓存
        $redis = Redis::connection('ddz_web_m');
        $redis->hset($redis_key,$redis_key,json_encode($data));
        return $data;

    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-04-16
     * @获取老黄历
     * @license   [license]
     * @version   [version]
     * @return    [type]      [description]
     */
     protected function getCalendarList()
    {
    	$todayTime = date('Y-m-d',time());//获取当天时间
    	$todayTime = strtotime($todayTime);

    	$where = [['time','=',$todayTime]];

    	$calendar = Calendar::where($where)
    						->first();

    	if (is_null($calendar)) {
    		return [];
    	}

    	$data['id'] = $calendar->id;

        //将宜内容转换成带p标签的数组
        $good_content = explode(',', $calendar->good_content);
        $good_content = array_map(function($val){ return '<p>'.$val.'</p>';}, $good_content);

        //将忌内容转换成带p标签的数组
        $bad_content = explode(',', $calendar->bad_content);
        $bad_content = array_map(function($val){ return '<p>'.$val.'</p>';}, $bad_content);

    	$data['good_content'] = $good_content;
    	$data['bad_content'] = $bad_content;
    	$data['person_name'] = $calendar->person_name;
    	$data['person_word'] = $calendar->person_word;
    	$data['avatar'] = $this->getFullUrl($calendar->avatar);
    	$data['is_show'] = $calendar->is_show;
    	$data['tip'] = $calendar->tip;
    	$data['time'] = $calendar->time;

    	return $data;
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-04-16
     * @获取图文列表
     */
    protected function getContentList()
    {
        $where = [
            ['is_home','=','1'],
            ['is_show','=','1'],
        ];

        //获取图文标签
        $contentTag = ContentTag::select('name','desc','created_at','id')
                              ->where($where)
                              ->orderBy('sort','desc')
                              ->orderBy('created_at','desc')
                              ->first();   

        if (is_null($contentTag)) {
            return [];
        }

        $content['id'] =   $contentTag->id;                      
        $content['name'] =  base64_decode($contentTag->name);                     
        $content['desc'] =   $contentTag->desc;
        $content['created_at'] =  $contentTag->created_at->format('Y-m-d');
       

    	$todayTime = date('Y-m-d',time());//获取当天时间
    	$todayTime = strtotime($todayTime);

    	$where = [
            ['is_show','=','1'],
            ['time','<=',$todayTime],
            ['tag_id','=',$contentTag->id],
        ];

    	$column = Content::select('sort','title','time','cover','tag_id','content','id')
    					->where($where)
    					->orderBy('sort','desc')
    					->orderBy('created_at','desc')
    					->limit(3)
    					->get();
    	if ($column->isEmpty()) {
    		return [];
    	}

    	$data = [];

    	foreach ($column as $key) {
            $list['id'] = $key->id;
            $list['sort'] = $key->sort;
    		$list['title'] = $key->title;
    		$list['time'] = $key->time;
    		$list['cover'] = $this->getFullUrl($key->cover);
    		$list['tag_id'] = $key->tag_id;
    		$list['content'] = $key->content;
    		$data[] = $list;

    	}

        $content['list'] = $data;
    	return $content;
    }

}
