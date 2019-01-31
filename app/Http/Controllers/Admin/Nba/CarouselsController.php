<?php

namespace App\Http\Controllers\Admin\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\CommonController;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Input;
use Route;
use App\Models\Nba\Carousel;
use Illuminate\Support\Facades\Storage;

class CarouselsController extends CommonController
{
    protected  $nba_carousel_list_key = 'nba_carousel_list';//轮播图缓存

    protected $type = [
                            0 => '链接',
                            1 => '腾讯视频',
                            2 => 'mp4视频',
                            3 => '漫画',
                            4 => '图文',
                        ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $where = [];
        $search = [];

        $showPage = $request->showPage?$request->showPage:10;
        
        if (strlen($title = $request->input('title')) > 0) {
            $where[] =  ['title','like','%'.$title.'%'];
            $search['title'] = $title;
        }
        if ($request->input('type') != '' && $request->input('type') != -2) {
            $where[] =  ['type','=',$request->input('type')];
            $search['type'] = $request->input('type');
        }

        $carousel = Carousel::where($where)->orderBy('sort','asc')->paginate($showPage);

        $type = $this->type;

        return view('admin.nba.carousel.list',compact('carousel','type','search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $carousel = new carousel();

        $type = $this->type;

        return view('admin.nba.carousel.edit',compact('carousel','type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'type' => 'required',
            'pic'  => 'required',
         ]);
        $carousel = new carousel();

        $type = $request->input('type');
        $third_id = $request->input('third_id');
        $url = $request->input('url');
        $carousel->sort = $request->sort|0;

        if ($type == 1 || $type == 3 || $type == 4) {
           if (is_null($third_id) || empty($third_id)) {
               return $this->error('请填写third_id');
            } 
            $carousel->third_id = $third_id;
        }

        if ($type == 2 || $type == 0) {
            if (is_null($url) || empty($url)) {
               return $this->error('请填写链接地址');
            } 
            $carousel->url = $url;
        }

        $carousel->title = $request->input('title');
        $carousel->type = $type;
        $carousel->is_show = $request->input('is_show',1);
        $carousel->pic_type = 1;

        //图片上传
        if ($request->file('pic')) {
            try {
                if (!$path = $this->uploadImageData('pic',['png','jpeg','jpg'],'uploads/nba/carousel')) {
                   return $this->error('图片保存失败');
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            $carousel->img = $path;
        }
        if (!$carousel->save()) {
            return $this->error('添加轮播图失败');
        }
        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_carousel_list_key);//清除轮播图缓存

        return $this->success('添加轮播图成功');
    }

    /**
     *修改显示状态
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

        $carousel = Carousel::find($id);

        if (is_null($carousel)) {
            return $this->error('获取数据失败');
        }

        if ($is_show == 0) {
            $carousel->is_show = 1;
        }else if($is_show == 1){
            $carousel->is_show = 0;
        }
       
        if (!$carousel->save()) {
            return $this->error('修改状态失败');
        }
        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_carousel_list_key);//清除轮播图缓存
        return $this->success('修改状态成功');
    }

    /**
     * 修改页面
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!intval($id)) {
            return $this->error('非法参数');
        }

        if (is_null($carousel = Carousel::find($id))) {
           return $this->error('获取数据失败');
        }
        // dd($carousel);
        // dd($carousel->sort);
        $type = $this->type;
        return view('admin.nba.carousel.edit',compact('carousel','type'));

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
       $data = $request->post();
       unset($data['_method']);
       unset($data['_token']);

       if (is_null($data['sort'])) {
            $data['sort'] = 0;
       }
       $this->validate($request, [
            'type' => 'required',
            'is_show' => 'required',
       ]);

       if ($data['type'] == 1 || $data['type'] == 3 || $data['type'] == 4) {
           if (is_null($data['third_id']) || empty($data['third_id'])) {
               return $this->error('请填写third_id');
            } 
            unset($data['url']);
        }

        if ($data['type'] == 2 || $data['type'] == 0) {
            if (is_null($data['url']) || empty($data['url'])) {
               return $this->error('请填写链接地址');
            } 
            unset($data['third_id']);
        }
         //图片上传
        if ($request->file('pic')) {
            try {
                if (!$path = $this->uploadImageData('pic',['png','jpeg','jpg'],'uploads/nba/carousel')) {
                   return $this->error('图片保存失败');
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            $data['img'] = $path;
        }
        if (!Carousel::where('id',$id)->update($data)) {
            return $this->error('修改轮播图失败');
        }
        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_carousel_list_key);//清除轮播图缓存

        return $this->success('修改轮播图成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $carousel = new Carousel();

       $redis = Redis::connection('ddz_web_m');
       $redis->del($this->nba_carousel_list_key);//清除轮播图缓存
       
       return $this->doDelete($id,$carousel);
    }

}
