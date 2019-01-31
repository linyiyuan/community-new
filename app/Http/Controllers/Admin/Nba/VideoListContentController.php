<?php

namespace App\Http\Controllers\Admin\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\CommonController;
use App\Models\Nba\VideoTag;
use App\Models\Nba\VideoList;
use DB;

class VideoListContentController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request->id; //拿到视频集合id

        if (!intval($id)) {
            return $this->error('非法参数');
        }
        
        $videoIds = DB::table('nba_list_video')
                      ->where('video_list_id',$id)
                      ->select('video_id')
                      ->get();

        $videoIds = array_column($this->toArray($videoIds), 'video_id');

        $videoListContent = DB::table('nba_video')
                              ->whereIn('id',$videoIds)
                              ->orderBy('id','desc')
                              ->paginate(10);

        $videoTag = $this->getVideoTag();

        // dd($videoListContent);
        return view('admin.nba.video_list_content.list',compact('videoListContent','videoTag','id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!intval($id=$request->id)) {
            return $this->error('非法参数');
        }

        $videoList = VideoList::find($id);

        return view('admin.nba.video_list_content.edit',compact('videoList'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
           [ 'video_id' => 'required',
            'video_list_id' => 'required',
           ]
        );
        $data = $request->post();
        unset($data['_token']);
        unset($data['_token']);

        //判断数据库是否已经存在改关系
        $checkExist = DB::table('nba_list_video')->where($data)->first();

        if (!is_null($checkExist)) {
            return $this->error('该视频已经存在该集合');
        }

        $checkVideoExist = DB::table('nba_video')->where('id',$request->video_id)->first();
        $checkVideoListExist = DB::table('nba_video_list')->where('id',$request->video_list_id)->first();

        if (is_null($checkVideoExist)) {
            return $this->error('不存在该视频');
        }
        if (is_null($checkVideoListExist)) {
            return $this->error('不存在该视频集合');
        }

       if(!DB::table('nba_list_video')->insert($data)){
            return $this->error('添加视频到集合失败');
       }

       return $this->success('添加视频到集合成功');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
       if (!intval($video_id=$id)) {
           return $this->error('获取视频id失败');
       }
       if (!intval($video_list_id=$request->video_list_id)) {
           return $this->error('获取集合id失败');
       }

       if (!DB::table('nba_list_video')->where(['video_id' => $video_id , 'video_list_id' => $video_list_id])->delete()) {
          return $this->ajaxResponse(500,'移除视频失败');
       }

        return $this->ajaxResponse(200,'移除视频成功');

        

    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-04-09
     * @copyright 获取视屏标签
     */
    protected function getVideoTag()
    {
        $videoTag = VideoTag::all();

        $videoTag = $this->toArray($videoTag);

        $videoTag = array_column($videoTag, 'name','id');

        $videoTag = array_map(function($val){ return base64_decode($val);},$videoTag );

        return $videoTag;
    }
}
