<?php

namespace App\Http\Controllers\Admin\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\CommonController;
use App\Models\Nba\Content;
use App\Models\Nba\ContentTag;
use Illuminate\Support\Facades\Redis;

class ContentController extends CommonController
{

    protected $nba_content_list_key = 'nba_content_list';//图文缓存

    protected $nba_content_detail_id_key = 'nba_content_detail_id:';//图文详情缓存

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
             $where = [['tag_id','=',$tag_id]];
             $search['tag_id'] = $tag_id;
        }

        $content = Content::where($where)
                          ->orderBy('id','desc')
                          ->paginate(10);

        $contentTag = $this->getContentTag();


        return view('admin.nba.content.list',compact('content','contentTag','search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $content = new Content();

        $contentTag = $this->getContentTag();

        return view('admin.nba.content.edit',compact('content','contentTag'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->post();
        $data['time'] = strtotime($data['time']);
       
        $this->validate($request,[
            'time' => 'required|date',
            'title' => 'required',
            'content' => 'required',
        ]);

        $content = new Content();

        if ($request->file('cover')) {
            try {
                if (!$path = $this->uploadImageData('cover',['png','jpeg','jpg'],'uploads/nba/content/cover')) {
                   return $this->error('图片保存失败');
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            $content->cover = $path;
        }

         $ip = ['211.159.184.31' => '10.104.229.225',
               '139.199.0.227' => '10.104.121.181',
               '127.0.0.1' => '127.0.0.1',
               '123.207.77.220' => '10.135.133.37',
         ];

        if (in_array($_SERVER["SERVER_ADDR"],$ip)) {
           if (!preg_match('/'.$_SERVER["SERVER_ADDR"].'/', $data['content'])) {

               $ip_rev = array_flip($ip);
               $ip = $ip_rev[$_SERVER["SERVER_ADDR"]];
               
                preg_match_all('/\/ueditor.*?(jpg|jpeg|gif|png)/', $data['content'], $m);
                foreach ($m[0] as $key => $value) {
                   $data['content'] = str_replace($value, 'http://'.$ip.':'.$_SERVER["SERVER_PORT"].$value, $data['content']);
                }
           }
        }else{
            return $this->error('系统错误，请联系管理员');
        }


        $content->title = $data['title'];
        $content->admin_id = $data['admin_id']|1;
        $content->time = $data['time'];
        $content->tag_id = $data['tag_id']|0;
        $content->sort = $data['sort']|0;
        $content->content = $data['content'];

        if (!$content->save()) {
            $this->removeFile($path);
            return $this->error('添加图文失败');
        }

        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_content_list_key);//清除图文缓存
        return $this->success('添加图文成功');
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $content = new Content();

        $is_show = $request->is_show;

        $redis = Redis::connection('ddz_web_m');

        $redis->del($this->nba_content_list_key);//清除图文缓存

        $redis->del($this->nba_content_detail_id_key.$id);//清除图文详情缓存

        return $this->changeIsShow($content,$id,$is_show);
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

        if (is_null($content = Content::find($id))) {
           return $this->error('获取数据失败');
        }

        $contentTag = $this->getContentTag();

        return view('admin.nba.content.edit',compact('content','contentTag'));
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

        if (is_null($content = Content::find($id))) {
           return $this->error('获取数据失败');
        }

        $data = $request->post();
        $data['time'] = strtotime($data['time']);
       
        $this->validate($request,[
            'time' => 'required|date',
            'title' => 'required',
            'content' => 'required',
        ]);


        if ($request->file('cover')) {
            try {
                if (!$path = $this->uploadImageData('cover',['png','jpeg','jpg'],'uploads/nba/content/cover')) {
                   return $this->error('图片保存失败');
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            $content->cover = $path;

        }

        $ip = ['211.159.184.31' => '10.104.229.225',
               '139.199.0.227' => '10.104.121.181',
               '127.0.0.1' => '127.0.0.1',
               '123.207.77.220' => '10.135.133.37',
         ];
        if (in_array($_SERVER["SERVER_ADDR"],$ip)) {
            $ip_rev = array_flip($ip);
               $ip = $ip_rev[$_SERVER["SERVER_ADDR"]];
               
                preg_match_all('/src=\"\/ueditor.*?(jpg|jpeg|gif|png)\"/', $data['content'], $m); 
                foreach ($m[0] as $key => $value) {
                    preg_match('/\/ueditor.*?(jpg|jpeg|gif|png)/', $value,$n);
                    $n[0] = str_replace("/","\/",$n[0]);
                    $data['content'] = preg_replace('/src=\"'.$n[0].'\"/', 'src="http://'.$ip.':'.$_SERVER["SERVER_PORT"].$n[0].'"', $data['content']);                  
                }
        }else{
            return $this->error('系统错误，请联系管理员');
        }
        $content->title = $data['title'];
        $content->admin_id = $data['admin_id']|1;
        $content->time = $data['time'];
        $content->tag_id = $data['tag_id']|0;
        $content->sort = $data['sort']|0;
        $content->content = $data['content'];

        if (!$content->save()) {
            $this->removeFile($path);
            return $this->error('修改图文失败');
        }

        $redis = Redis::connection('ddz_web_m');
        $redis->del($this->nba_content_list_key);//清除图文缓存
        $redis->del($this->nba_content_detail_id_key.$id);//清除图文详情缓存
        return $this->success('修改图文成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = new Content();

        $redis = Redis::connection('ddz_web_m');
        
        $redis->del($this->nba_content_list_key);//清除图文缓存

        $redis->del($this->nba_content_detail_id_key.$id);//清除图文详情缓存

        return $this->doDelete($id,$model);
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-04-12
     * @得到图文标签
     */
    protected function getContentTag()
    {
        $contentTag = ContentTag::select('id','name')
                                ->get();
        $contentTag = $this->toArray($contentTag);

        $contentTag = array_column($contentTag, 'name','id');

        return  array_map(function($val){ return base64_decode($val);},$contentTag);
    }
}
