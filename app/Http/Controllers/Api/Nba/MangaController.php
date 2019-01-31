<?php

namespace App\Http\Controllers\Api\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Input,Response,DB,Route;
use App\Models\Nba\Manga;
use App\Models\Nba\MangaTag;
use App\Models\Nba\MangaImg;
use Illuminate\Support\Facades\Redis;

/**
 * 获取漫画列表和漫画详情数据
 */
class MangaController extends BaseController
{
	/**
	 * @Author    linyiyuan
	 * @DateTime  2018-04-17
	 * @获取漫画列表
	 */
    public function getMangaList(Request $request)
    {
        $redis_key  = 'nba_manga_list';//声明漫画缓存的键

        //先从redis缓存拿数据,如果有就返回
        $redis = Redis::connection('ddz_web');
        if ($data=$redis->hget($redis_key,$redis_key)) {
            $data = json_decode($data);
            return $this->successReturn(200,$data);
        }

        //从数据库里拿数据
    	$where = [];
    	if (intval($tag_id = $request->tag_id)) {
    		$where[] = ['id','=',$tag_id];
    	}
    	$where[] = ['is_show','=','1'];

    	$mangaTag = mangaTag::select('name','desc','id','sort')
    						->where($where)
    						->orderBy('sort','desc') 
    						->orderBy('created_at','desc') 
    						->get();
    	$data = [];
    	foreach ($mangaTag as $key ) {
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

    		$manga = Manga::select('id','title','type','tag_id','cover','sort','created_at')
    					  ->where($where)
    					  ->orderBy('sort','desc')
    					  ->orderBy('created_at','desc')
    					  ->get();

    		$one = [];
    		foreach ($manga as $key) {
    			$mangaList = [];
    			$mangaList['id'] = $key->id;
    			$mangaList['title'] = $key->title;
    			$mangaList['type'] = $key->type;
    			$mangaList['tag_id'] = $key->tag_id;
    			$mangaList['cover'] = $this->getFullUrl($key->cover);
    			$mangaList['sort'] = $key->sort;
    			$mangaList['created_at'] = $key->created_at->format('Y-m-d');
    			$one[] = $mangaList;
    		}

    		$list['list'] = $one;

    		$data[] = $list;
    	}

        //加入缓存
        $redis = Redis::connection('ddz_web_m');
        $redis->hset($redis_key,$redis_key,json_encode($data));
        
    	return $this->successReturn(200,$data);
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-04-17
     * 漫画详情内容
     * @param     [integer]      $id [漫画id]
     */
    public function getMangaDetail($id)
    {
    	if (!intval($id)) {
    		return $this->errorReturn(500,'非法参数');
    	}

         //先从缓存里拿数据
        $redis_key = 'nba_manga_detail_id:'.$id; //漫画详情缓存

        $redis = Redis::connection('ddz_web');
        if ($data=$redis->hget($redis_key,$redis_key)) {
            $data = json_decode($data);
            return $this->successReturn(200,$data);
        }

    	$where = [
    		['time','<=', $this->getNowTime()],
    		['is_show','=','1'],
            ['id','=',$id]
    	];
    	$mangaDetail = Manga::select('id','type','cover','title','created_at')
    						->where($where)
    						->find($id);

    	if (is_null($mangaDetail)) {
    		return $this->successReturn(200,[]);
    	}

    	$data['id'] = $mangaDetail->id;
    	$data['title'] = $mangaDetail->title;
    	$data['cover'] = $this->getFullUrl($mangaDetail->cover);
    	$data['type'] = $mangaDetail->type;
    	$data['created_at'] = $mangaDetail->created_at->format('Y-m-d');

    	$mangaImg = MangaImg::select('img')
    						   ->where('manga_id',$id)
    						   ->get();	

    	if ($mangaImg->isEmpty()) {
    		$data['imgs'] = [];
    	}else{
    		$mangaImg = $this->toArray($mangaImg);	
    		$mangaImg = array_map(function($val){ return $this->getFullUrl($val['img']); }, $mangaImg);
    		$data['imgs'] = $mangaImg;
    	}
        
        $redis = Redis::connection('ddz_web_m');
        $redis->hset($redis_key,$redis_key,json_encode($data));//将漫画详情存入缓存

    	return $this->successReturn(200,$data);
    }
}
