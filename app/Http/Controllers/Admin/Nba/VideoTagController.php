<?php

namespace App\Http\Controllers\Admin\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Nba\VideoTag;
use App\Http\Controllers\Base\CommonController;
use Illuminate\Support\Facades\Redis;
use DB;

class VideoTagController extends CommonController
{

    protected $nba_video_list_key = 'nba_video_list';//视频缓存

    protected $nba_column_list_key = 'nba_column_list';//图文缓存

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videoTag = VideoTag::from('nba_video_tag as t')
                            ->select(DB::raw('count(v.id) as count, t.id, t.name, t.desc, t.is_show,t.sort'))
                            ->orderBy('t.id','desc')
                            ->leftjoin('nba_video as v','t.id','=','v.tag_id')
                            ->groupBy('t.id','t.name', 't.desc', 't.is_show','t.sort')
                            ->paginate(10);

        return view('admin.nba.video_tag.list',compact('videoTag'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $videoTag = new VideoTag();
        
        return view('admin.nba.video_tag.edit',compact('videoTag'));
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
            'name' => 'required',
            'sort' => 'integer',
        ]);

        $videoTag  = new VideoTag();

        $videoTag->name = base64_encode($request->name);
        $videoTag->sort = $request->sort;
        $videoTag->desc = $request->desc;

        if(!$videoTag->save()){
            return $this->error('添加视频标签失败');
        }
        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_video_list_key);// 清除视频缓存
        $redis->del($this->nba_column_list_key);//清除图文缓存
        return $this->success('添加视频标签成功');

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

        $videoTag = VideoTag::find($id);

        if (is_null($videoTag)) {
            return $this->error('获取数据失败');
        }

        $is_show = $request->is_show;

        if ($is_show == '') {
           return $this->error('获取当前状态失败');
        }

        if ($is_show == 0) {
            $videoTag->is_show = 1;
        }else if($is_show == 1){
            $videoTag->is_show = 0;
        }

        if (!$videoTag->save()) {
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
        $videoTag = VideoTag::find($id);

        if (is_null($videoTag)) {
           return $this->error('获取数据失败');
        }

        return view('admin.nba.video_tag.edit',compact('videoTag'));
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
       $this->validate($request,[
            'name' => 'required',
            'sort' => 'integer',
        ]);
       if (!intval($id)) {
            return $this->error('非法参数');
        }

        $videoTag = VideoTag::find($id);

        if (is_null($videoTag)) {
           return $this->error('获取数据失败');
        }

        $videoTag->name = base64_encode($request->name);
        $videoTag->sort = $request->sort;
        $videoTag->desc = $request->desc;

        if (!$videoTag->save()) {
            return $this->error('修改视频标签失败');
        }
        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_video_list_key);// 清除视频缓存
        $redis->del($this->nba_column_list_key);//清除图文缓存
        return $this->success('修改视频标签成功');

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
