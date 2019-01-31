<?php

namespace App\Http\Controllers\Admin\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Nba\Video;
use App\Models\Nba\VideoTag;
use App\Models\Nba\VideoList;
use App\Http\Controllers\Base\CommonController;
use Illuminate\Support\Facades\Redis;
use DB;

class VideoController extends CommonController
{

    protected $nba_video_list_key = 'nba_video_list';//视频缓存

    protected $nba_column_list_key = 'nba_column_list';//图文缓存


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $where = [];
        $search = [];

        if (strlen($request->tag_id) > 0 && $request->tag_id != '-2' ) {
            array_push($where, ['tag_id',$request->tag_id]);
            $search['tag_id'] = $request->tag_id;
        }

        if (strlen($request->video_list_id) > 0 && $request->video_list_id != '-2') {
           array_push($where, ['video_list_id',$request->video_list_id]);
           $search['video_list_id'] = $request->video_list_id;
        }

        $video = Video::from('nba_video as v')
                        ->select(DB::Raw('DISTINCT(v.id)'),'tag_id','data','cover','title','sort','is_show','time','type'  )
                        ->where($where)
                        ->orderBy('v.id','desc')
                        ->leftjoin('nba_list_video as l','l.video_id','=','v.id')
                        ->Paginate(10);

        $videoTag = $this->getVideoTag();
        $videoList = $this->getVideoList();

        
        return view('admin.nba.video.list',compact('video','videoTag','videoList','search'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $video = new Video();

        $videoTag = $this->getVideoTag();
        

        return view('admin.nba.video.edit',compact('video','videoTag'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'time' => 'date|required',
            'title' => 'required',
            'tag_id' => 'required',
            'data' => 'required',
            'cover' => 'required',
            'type' => 'required',

        ]);
        
        $data = $request->post();

        $video = new Video();

        $data['time'] = strtotime($data['time']);

        if ($request->file('cover')) {
            try {
                if (!$path = $this->uploadImageData('cover',['png','jpeg','jpg'],'uploads/nba/video')) {
                   return $this->error('图片保存失败');
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            $video->cover = $path;
        }


        $video->title = $data['title'];
        $video->admin_id = $data['admin_id'];
        $video->time = $data['time'];
        $video->tag_id = $data['tag_id'];
        $video->sort = $data['sort']|0;
        $video->data = $data['data'];
        $video->type = $data['type'];

        if (!$video->save()) {
            return $this->error('添加视频失败');
        }
        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_video_list_key);// 清除视频缓存
        $redis->del($this->nba_column_list_key);//清除图文缓存
        return $this->success('添加视频成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
         if (!intval($id)) {
           return $this->error('非法参数');
        }
        $is_show = $request->input('is_show');

        if ($is_show == '') {
            return $this->error('状态非法参数');
        }

        $video = Video::find($id);

        if (is_null($video)) {
            return $this->error('获取数据失败');
        }

        if ($is_show == 0) {
            $video->is_show = 1;
        }else if($is_show == 1){
            $video->is_show = 0;
        }
       
        if (!$video->save()) {
            return $this->error('修改状态失败');
        }
        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_video_list_key);// 清除视频缓存
        $redis->del($this->nba_column_list_key);//清除图文缓存
        return $this->success('修改状态成功');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!intval($id)) {
           return $this->error('非法参数');
        }

        $video = Video::find($id);

        if (is_null($video)) {
            return $this->error('获取数据失败');
        }

        $videoTag = $this->getVideoTag();
        return view('admin.nba.video.edit',compact('video','videoTag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!intval($id)) {
            return $this->error('非法参数');
        }

        $video = Video::find($id);

        if (is_null($video)) {
            return $this->error('获取数据失败');
        }

        $this->validate($request,[
            'time' => 'date|required',
            'title' => 'required',
            'tag_id' => 'required',
            'data' => 'required',
            'type' => 'required',
        ]);
        
        $data = $request->post();
        // dd($data);
        unset($data['_method']);
        unset($data['s']);

        $data['time'] = strtotime($data['time']);
        if ($request->file('cover')) {
            try {
                if (!$path = $this->uploadImageData('cover',['png','jpeg','jpg'],'uploads/nba/video')) {
                   return $this->error('图片保存失败');
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            $video->cover = $path;
        }

        $video->title = $data['title'];
        $video->admin_id = $data['admin_id'];
        $video->time = $data['time'];
        $video->tag_id = $data['tag_id'];
        $video->sort = $data['sort']?$data['sort']:0;
        $video->data = $data['data'];
        $video->type = $data['type'];

        if (!$video->save()) {
            return $this->error('修改视频失败');
        }
        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_video_list_key);// 清除视频缓存
        $redis->del($this->nba_column_list_key);//清除图文缓存
        return $this->success('修改视频成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $video = new Video();
        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_video_list_key);// 清除视频缓存
        $redis->del($this->nba_column_list_key);//清除图文缓存
        return $this->doDelete($id,$video);
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-04-09
     * @copyright 获取视频标签
     */
    protected function getVideoTag()
    {
        $videoTag = VideoTag::select('id','name')->get();

        $videoTag = $this->toArray($videoTag);

        $videoTag = array_column($videoTag, 'name','id');

        $videoTag = array_map(function($val){ return base64_decode($val);},$videoTag );

        return $videoTag;
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-04-09
     * @copyright 获取视频集合
     */
    protected function getVideoList()
    {
        $videoList = VideoList::select('id','list_name')->get();

        $videoList = $this->toArray($videoList);

        $videoList = array_column($videoList, 'list_name','id');


        return $videoList;
    }
}
