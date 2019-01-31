<?php

namespace App\Http\Controllers\Admin\HelixSaga;

use Illuminate\Http\Request;
use App\Models\HelixSaga\DataDictionary;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\CommonController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Admin\HelixSaga\DataProcessingController;

/**
 * 螺旋英雄谭数据表(Excel)处理
 */
class DataDictionaryController extends CommonController
{
    /**
     * 螺旋英雄谭数据列表
     */
    public function index()
    {   

        $dataDictionary =  DataDictionary::paginate(10);

        return view('admin.helixSaga.data_dictionary.index',compact('dataDictionary'));
    }

    /**
     * 添加数据表页面
     */
    public function create()
    {
        $dataDictionary = new DataDictionary();

        return view('admin.helixSaga.data_dictionary.edit',compact('dataDictionary'));
    }

    /**
     * 执行添加数据表操作
     */
    public function store(Request $request)
    {
         $dataDictionary = new DataDictionary();

         $this->validate($request,[
            'name' => 'required',
         ]);

         $dataDictionary->name = $request->name;

         if (is_null($file=$request->file('file'))) {
            return $this->error('请上传文件');
        }
            $fileType = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            // 得到上传文件源文件名
            $originalName = $file->getClientOriginalName();
            // 得到上传文件的后缀
            $ext = $file->getClientOriginalExtension();
            // 等到上传文件的类型
            $type = $file->getClientMimeType();
            // 得到上传文件的tmp绝对路径
            $realPath = $file->getRealPath();

            if (!in_array($type, $fileType)) {
               return $this->error("上传不是EXCEL格式");
            }
             if ($file->getSize() > 21971395) {
                return $this->error('上传EXCEL文件过大');
            }
        $dir = 'helix_haga/dataDictionary/'.$originalName;//拼接图片路径

        $uploadBool = Storage::disk('cosv5')->put($dir,file_get_contents($realPath));
        if (!$uploadBool) {
            return $this->error('上传文件失败');
        }

        $dataDictionary->url = env('COSV5_CDN').$dir;

        if (!$dataDictionary->save()) {
            return $this->error('添加失败');
        }

        Self::redisReset();//清除缓存
        return $this->success('添加成功');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
    }

    /**
     * 修改数据表页面
     */
    public function edit($id,Request $request)
    {
        if (!intval($id)) {
            return $this->error('非法参数');
        }

        if (is_null($dataDictionary=DataDictionary::find($id))) {
           return $this->error('获取数据失败');
        }

         return view('admin.helixSaga.data_dictionary.edit',compact('dataDictionary'));
    }

    /**
     * 执行修改数据表操作
     */
    public function update(Request $request, $id)
    {
        if (!intval($id)) {
            return $this->error('非法参数');
        }

        if (is_null($dataDictionary=DataDictionary::find($id))) {
           return $this->error('获取数据失败');
        }

         $this->validate($request,[
            'name' => 'required',
         ]);

         $dataDictionary->name = $request->name;

         if ($file=$request->file('file')) {
            $fileType = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            // 得到上传文件源文件名
            $originalName = $file->getClientOriginalName();
            // 得到上传文件的后缀
            $ext = $file->getClientOriginalExtension();
            // 等到上传文件的类型
            $type = $file->getClientMimeType();
            // 得到上传文件的tmp绝对路径
            $realPath = $file->getRealPath();

            if (!in_array($type, $fileType)) {
               return $this->error("上传不是zip格式");
            }
             if ($file->getSize() > 21971395) {
                return $this->error('上传zip文件过大');
            }
            $dir = 'helix_haga/dataDictionary/'.$originalName;//拼接图片路径

            $uploadBool = Storage::disk('cosv5')->put($dir,file_get_contents($realPath));
            if (!$uploadBool) {
                return $this->error('上传文件失败');
            }
             $dataDictionary->url = env('COSV5_CDN').$dir;
         }
           
            if (!$dataDictionary->save()) {
                return $this->error('修改失败');
            }
            
            Self::redisReset();//清除缓存
            return $this->success('修改成功');
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

    protected static function redisReset()
    {
        $redis = Redis::connection('ddz_web_m');
        $redis->del('helix_saga_role');//清除角色缓存
        $redis->del('helix_saga_tool');//清除道具缓存
        $redis->del('helix_saga_skill');//清除技能缓存

        $dataProcessing = new DataProcessingController();

        $dataProcessing->getRoleList();
        $dataProcessing->getToolList();
        $dataProcessing->getSkillList();
    }
}
