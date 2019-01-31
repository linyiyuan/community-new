<?php

namespace App\Http\Controllers\Admin\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\CommonController;
use App\Models\Nba\VideoList;
use DB;

class VideoListController extends CommonController
{ 
    /**
     * 视频集合列表
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videoList = VideoList::select(DB::raw('count(l.video_list_id) as count, id, list_name, sort, is_show'))
                              ->orderBy('id','desc')
                              ->leftjoin('nba_list_video as l','id','=','video_list_id')
                              ->groupBy('id','list_name','sort','is_show')
                              ->paginate(10);


        return view('admin.nba.video_list.list',compact('videoList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $videoList = new VideoList();

        return view('admin.nba.video_list.edit',compact('videoList'));
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
            'list_name' => 'required|unique:nba_video_list',
        ]);

        $videoList = new VideoList();

        if (!is_null($request->sort)) {
            $videoList->sort = $request->sort;
        }

        $videoList->list_name = $request->list_name;

        if (!$videoList->save()) {
            return $this->error('添加视频集合失败');
        }
        return $this->success('添加视频集合成功');
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

        $videoList = videoList::find($id);

        if (is_null($videoList)) {
            return $this->error('获取数据失败');
        }

        $is_show = $request->is_show;

        if ($is_show == '') {
           return $this->error('获取当前状态失败');
        }

        if ($is_show == 0) {
            $videoList->is_show = 1;
        }else if($is_show == 1){
            $videoList->is_show = 0;
        }

        if (!$videoList->save()) {
            return $this->error('修改状态失败');
        }
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

        $videoList = VideoList::find($id);

        if (is_null($videoList)) {
            return $this->error('获取数据失败');
        }

        return view('admin.nba.video_list.edit',compact('videoList'));
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

         $this->validate($request,[
            'list_name' => 'required',
        ]);

        $videoList =  VideoList::find($id);

        if (is_null($videoList)) {
            return $this->error('获取数据失败');
        }

        if (!is_null($request->sort)) {
            $videoList->sort = $request->sort;
        }else{
            $videoList->sort = 0;
        }

        $videoList->list_name = $request->list_name;

        if (!$videoList->save()) {
            return $this->error('修改视频集合失败');
        }
        return $this->success('修改视频集合成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
