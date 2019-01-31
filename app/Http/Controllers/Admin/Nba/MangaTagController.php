<?php

namespace App\Http\Controllers\Admin\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Nba\Manga;
use App\Models\Nba\MangaTag;
use App\Http\Controllers\Base\CommonController;
use Illuminate\Support\Facades\Redis;
use DB;

class MangaTagController extends CommonController
{

    protected $nba_manga_list_key = 'nba_manga_list';//漫画缓存
    
    protected $nba_column_list_key = 'nba_column_list';//漫画缓存


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $field = '*';

       $mangaTag = MangaTag::select(DB::raw('count(v.id) as count, nba_manga_tag.id, nba_manga_tag.name, nba_manga_tag.desc, nba_manga_tag.is_show, nba_manga_tag.sort'))
                    ->orderBy('nba_manga_tag.id','desc')
                    ->leftjoin('nba_manga as v','nba_manga_tag.id','=','v.tag_id')
                    ->groupBy('nba_manga_tag.id', 'nba_manga_tag.name', 'nba_manga_tag.desc', 'nba_manga_tag.is_show','nba_manga_tag.sort')
                    ->paginate(10);

       return view('admin.nba.manga_tag.list',compact('mangaTag'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mangaTag  = new MangaTag();

        return view('admin.nba.manga_tag.edit',compact('mangaTag'));
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

        $mangaTag  = new MangaTag();

        $mangaTag->name = base64_encode($request->name);
        $mangaTag->sort = $request->sort;
        $mangaTag->desc = $request->desc;

        if(!$mangaTag->save()){
            return $this->error('添加漫画标签失败');
        }

        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_manga_list_key);//清除漫画缓存
        $redis->del($this->nba_column_list_key);//清除图文缓存
        return $this->success('添加漫画标签成功');
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

        $mangaTag = MangaTag::find($id);

        if (is_null($mangaTag)) {
            return $this->error('获取数据失败');
        }

        $is_show = $request->is_show;

        if ($is_show == '') {
           return $this->error('获取当前状态失败');
        }

        if ($is_show == 0) {
            $mangaTag->is_show = 1;
        }else if($is_show == 1){
            $mangaTag->is_show = 0;
        }

        if (!$mangaTag->save()) {
            return $this->error('修改状态失败');
        }
        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_manga_list_key);//清除漫画缓存
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
        $mangaTag = MangaTag::find($id);

        if (is_null($mangaTag)) {
           return $this->error('获取数据失败');
        }
        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_manga_list_key);//清除漫画缓存
        $redis->del($this->nba_column_list_key);//清除图文缓存
        return view('admin.nba.manga_tag.edit',compact('mangaTag'));
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
            'sort' => 'integer|required|max:200',
        ]);
       if (!intval($id)) {
            return $this->error('非法参数');
        }

        $mangaTag = MangaTag::find($id);

        if (is_null($mangaTag)) {
           return $this->error('获取数据失败');
        }

        $mangaTag->name = base64_encode($request->name);
        $mangaTag->sort = $request->sort;
        $mangaTag->desc = $request->desc;

        if (!$mangaTag->save()) {
            return $this->error('修改漫画标签失败');
        }
        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_manga_list_key);//清除漫画缓存
        $redis->del($this->nba_column_list_key);//清除图文缓存
        return $this->success('修改漫画标签成功');   
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
