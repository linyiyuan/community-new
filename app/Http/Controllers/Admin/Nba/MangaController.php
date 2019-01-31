<?php

namespace App\Http\Controllers\Admin\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Nba\Manga;
use App\Models\Nba\MangaTag;
use App\Models\Nba\MangaImg;
use App\Http\Controllers\Base\CommonController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;
use DB;

class MangaController extends CommonController
{

    protected $nba_manga_list_key = 'nba_manga_list';//漫画缓存

    protected $nba_manga_detail_key =  'nba_manga_detail_id:';//漫画详情缓存

    protected $nba_column_list_key = 'nba_column_list';//首页栏目缓存
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where = [];
        $search = [];

        if (strlen($tag_id=$request->tag_id) > 0) {
            $where = [ ['tag_id','=',$tag_id] ];
            $search['tag_id'] = $tag_id;
        }

        $field = '*';
        $manga = Manga::where($where)
                        ->select($field)
                        ->orderBy('id','desc')
                        ->paginate(10);
        
        $mangaTag = $this->getMangaTag();//获取所有漫画标签

        return view('admin.nba.manga.list',compact('manga','mangaTag','search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $manga = new Manga();

        $mangaTag = $this->getMangaTag();

        return view('admin.nba.manga.edit',compact('manga','mangaTag'));
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
            'time' => 'required|date',
            'tag_id' => 'required',
            'cover' => 'required',
            'type' => 'required',
            'img' => 'required',
        ]);

        $data = $request->post();

        $manga = new Manga();

        $data['time'] = strtotime($data['time']);

        $imgs = $request->file('img');

    
        if ($request->file('cover')) {
            try {
                if (!$path = $this->uploadImageData('cover',['png','jpeg','jpg'],'uploads/nba/manga/cover')) {
                   return $this->error('图片保存失败');
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            $manga->cover = $path;
        }

        $manga->title = $data['title'];
        $manga->admin_id = $data['admin_id'];
        $manga->time = $data['time'];
        $manga->tag_id = $data['tag_id'];
        $manga->sort = $data['sort']?$data['sort']:0;
        $manga->type = $data['type'];

        DB::beginTransaction();

        if (!$manga->save()) {
            DB::rollback();
            return $this->error('添加漫画失败');
        }
        if (!is_null($imgs)) {
            foreach ($imgs as $k => $v) {
                  $mangaImg = new MangaImg();

                   try {
                        if (!$path = $this->uploadMoreImageData($v,['png','jpeg','jpg'],'uploads/nba/manga/content')) {
                           return $this->error('图片保存失败');
                        }
                    } catch (\Exception $e) {
                        return $this->error($e->getMessage());
                    }

                    $mangaImg->img = $path;
                    $mangaImg->manga_id = $manga->id;

                    if (!$mangaImg->save()) {
                        DB::rollback();
                        return $this->error('添加漫画图片失败');
                    }

             }
        }
       
        DB::commit();

        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_manga_list_key);//清除漫画缓存
        $redis->del($this->nba_column_list_key);//清除图文缓存
        return $this->success('添加漫画成功');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {

        $is_show = $request->is_show;

        $model = new Manga();

        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_manga_list_key);//清除漫画缓存
        $redis->del($this->nba_column_list_key);//清除图文缓存
        return $this->changeIsShow($model,$id,$is_show);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        if (!intval($id)) {
            return $this->error('非法参数');
        }

        $manga = Manga::find($id);

        $mangaTag  = $this->getMangaTag();

        $mangaImgs = MangaImg::where('manga_id',$id)->get();

        return view('admin.nba.manga.edit',compact('manga','mangaTag','mangaImgs'));
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

       $manga = Manga::find($id);

       if (is_null($manga)) {
           return $this->error('获取漫画数据失败');
       }
       $this->validate($request,[
            'time' => 'required|date',
            'tag_id' => 'required',
            'type' => 'required',
        ]);

        $data = $request->post();
        unset($data['_method']);

        $data['time'] = strtotime($data['time']);

        $imgs = $request->file('img');

        if ($request->file('cover')) {
            try {
                if (!$path = $this->uploadImageData('cover',['png','jpeg','jpg'],'uploads/nba/manga/cover')) {
                   return $this->error('图片保存失败');
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            $this->removeFile($manga->cover);
            $manga->cover = $path;
        }

        $manga->title = $data['title'];
        $manga->admin_id = $data['admin_id'];
        $manga->time = $data['time'];
        $manga->tag_id = $data['tag_id'];
        $manga->sort = $data['sort']?$data['sort']:0;
        $manga->type = $data['type'];

         DB::beginTransaction();

         if (!$manga->save()) {
             DB::rollback();
             return $this->error('修改漫画失败');
         }
        if (isset($data['img_id'])) {
             foreach ($data['img_id'] as $k => $v) {
                //判断是否有修改的照片，如果有则进行修改
                if ($request->file('img_'.$v)) {
                    $mangaImg = MangaImg::find($v);
                    if (is_null($mangaImg)) {
                        return $this->error('获取漫画图片数据失败');
                    }
                       try {
                            if (!$path = $this->uploadImageData('img_'.$v,['png','jpeg','jpg'],'uploads/nba/manga/content')) {
                               return $this->error('图片保存失败');
                            }
                        } catch (\Exception $e) {
                            return $this->error($e->getMessage());
                        }

                        $mangaImg->img = $path;

                        if (!$mangaImg->save()) {
                            DB::rollback();
                            return $this->error('修改漫画图片失败');
                        }
                }
            }
        }
        if (!is_null($imgs)) {
            foreach ($imgs as $k => $v) {
                  $mangaImg = new MangaImg();

                   try {
                        if (!$path = $this->uploadMoreImageData($v,['png','jpeg','jpg'],'uploads/nba/manga/content')) {
                           return $this->error('图片保存失败');
                        }
                    } catch (\Exception $e) {
                        return $this->error($e->getMessage());
                    }

                    $mangaImg->img = $path;
                    $mangaImg->manga_id = $manga->id;

                    if (!$mangaImg->save()) {
                        DB::rollback();
                        $this->removeFile($path);
                        return $this->error('添加漫画图片失败');
                    }

             }
        }

        DB::commit();
        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_manga_list_key);//清除漫画缓存
        $redis->del($this->nba_manga_detail_key.$id);//清除漫画详情缓存
        $redis->del($this->nba_column_list_key);//清除图文缓存
        return $this->success('修改漫画内容成功');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       if (!intval($id)) {
            return $this->ajaxResponse('500','非法参数');
        }

        $manga = Manga::find($id);

        if (is_null($manga)) {
            return $this->ajaxResponse('500','获取数据失败');
        }

        DB::beginTransaction();

        if (!$manga->delete()) {
            DB::rollback();
            return $this->ajaxResponse('500','删除漫画内容失败');
        }

        if (!MangaImg::where('manga_id',$id)->get()->isEmpty()) {
            if(!MangaImg::where('manga_id',$id)->delete()){
                DB::rollback();
                return $this->ajaxResponse('500','删除漫画图片失败');
            }
        }

        DB::commit();
        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_manga_list_key);//清除漫画缓存
        $redis->del($this->nba_manga_detail_key.$id);//清除漫画详情缓存
        $redis->del($this->nba_column_list_key);//清除图文缓存
        return $this->ajaxResponse('200','删除漫画成功');

    }


    protected function getMangaTag()
    {
        $mangaTag = MangaTag::select('id','name')
                    ->get();
        $mangaTag = $this->toArray($mangaTag);

        $mangaTag = array_column($mangaTag, 'name','id');

        $mangaTag = array_map(function($val){ return base64_decode($val);},$mangaTag);

        return $mangaTag;
    }
}
