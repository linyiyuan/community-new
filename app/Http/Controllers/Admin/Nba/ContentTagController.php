<?php

namespace App\Http\Controllers\Admin\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\CommonController;
use App\Models\Nba\Content;
use App\Models\Nba\ContentTag;
use Illuminate\Support\Facades\Redis;
use DB;


class ContentTagController extends CommonController
{
    protected $nba_content_list_key = 'nba_content_list';//图文缓存

    /**
     * 显示图文标签列表页
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contentTag = ContentTag::from('nba_content_tag as t')
                    ->select(DB::raw('count(v.id) as count, t.id, t.name, t.desc, t.is_home, t.sort,t.is_show'))
                    ->orderBy('t.id','desc')
                    ->leftjoin('nba_content as v','t.id','=','v.tag_id')
                    ->groupBy('t.id', 't.name', 't.desc', 't.is_home','t.sort','t.is_show')
                    ->paginate(10);

        return view('admin.nba.content_tag.list',compact('contentTag'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contentTag = new ContentTag();

        return view('admin.nba.content_tag.edit',compact('contentTag'));
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
            'sort' => 'integer|required|max:200',
        ]);

        $contentTag  = new ContentTag();

        $contentTag->name = base64_encode($request->name);
        $contentTag->sort = $request->sort;
        $contentTag->desc = $request->desc;

        if (!is_null($request->is_show)) {
            $contentTag->is_show = $request->is_show;
        }


        if (!is_null($request->is_home)) {
            $contentTag->is_home = $request->is_home;
        }

        if(!$contentTag->save()){
            return $this->error('添加图文标签失败');
        }

        Redis::del($this->nba_content_list_key);//清除图文缓存
        return $this->success('添加图文标签成功');
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

        if (is_null($contentTag = ContentTag::find($id))) {
            return $this->error('获取数据失败，请刷新重试');
        }

        if (!is_null($status=$request->is_show)) {
              if ($status == 0) {
                   $contentTag->is_show = 1;
              }else if($status == 1){
                   $contentTag->is_show = 0;
              }
              if (!$contentTag->save()) {
                 return $this->error('修改是否显示状态失败');
              }
            Redis::del($this->nba_content_list_key);//清除图文缓存
            return $this->success('修改是否显示状态成功');
        }

        if (!is_null($status=$request->is_home)) {
            if ($status == 0) {
                   $contentTag->is_home = 1;
              }else if($status == 1){
                   $contentTag->is_home = 0;
              }
              if (!$contentTag->save()) {
                 return $this->error('修改是否在首页显示状态失败');
              }
            return $this->success('修改是否在首页显示状态成功');
        }
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
        if (is_null($contentTag = ContentTag::find($id))) {
            return $this->error('获取数据失败，请刷新重试');
        }

        return view('admin.nba.content_tag.edit',compact('contentTag'));

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

        if (is_null($contentTag = ContentTag::find($id))) {
            return $this->error('获取数据失败，请刷新重试');
        }

        $this->validate($request,[
            'name' => 'required',
            'sort' => 'integer|required|max:200',
        ]);

        $contentTag->name = base64_encode($request->name);
        $contentTag->sort = $request->sort;
        $contentTag->desc = $request->desc;

        if (!is_null($request->is_show)) {
            $contentTag->is_show = $request->is_show;
        }


        if (!is_null($request->is_home)) {
            $contentTag->is_home = $request->is_home;
        }

        if(!$contentTag->save()){
            return $this->error('修改图文标签失败');
        }

        Redis::del($this->nba_content_list_key);//清除图文缓存
        return $this->success('修改图文标签成功');
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
