<?php

namespace App\Http\Controllers\Admin\HelixSaga;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\CommonController;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App\Models\HelixSaga\PicCatalog;

/**
 * 螺旋英雄谭图片管理
 */
class ImportPictureController extends CommonController
{
    /**
     * @Author    linyiyuan
     * @DateTime  2018-05-11
     * @上传zip跟上传图片页面
     */
    public function index()
    {      
        //获取所有目录名
        $picCatalog = PicCatalog::select('path')
                                ->get();

    	return view('admin.helixSaga.picture_upload.index',compact('picCatalog'));
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-05-11我
     * 上传zip文件并进行解压上传到腾讯云cos
     */
    public function upload(Request $request)
    {
        if (empty($path = $request->path)) {
            return $this->error('请选择文件目录');
        }


    	if (is_null($file=$request->file('file'))) {
    		return $this->error('请上传文件');
    	}
    	    $fileType = ['application/zip','application/x-zip-compressed'];
            // 得到上传文件源文件名
            $originalName = $file->getClientOriginalName();
            // 得到上传文件的后缀
            $ext = $file->getClientOriginalExtension();
            // 等到上传文件的类型
            $type = $file->getClientMimeType();

            // 得到上传文件的tmp绝对路径
            $realPath = $file->getRealPath();

            $name = str_replace('.zip', '', $originalName);

            if (!in_array($type, $fileType)) {
               return $this->error("上传不是zip格式");
            }
             if ($file->getSize() > 21971395) {
                return $this->error('上传zip文件过大');
            }
            $flieName = date('Y-m-d-H-i-s').'-'.uniqid().'.'.$ext;

            $bool= Storage::disk('public')->put('uploads/helix_saga/zip/'.$flieName,file_get_contents($realPath));
            // $bool= Storage::disk('cosv5')->put('helix_saga/zip'.'/'.$flieName,file_get_contents($realPath));
            if (!$bool) {
            	return $this->error('上传文件失败');
            }
            $bool = $this->UnzipZip('storage/uploads/helix_saga/zip/'.$flieName,$path,$name);
            if (!$bool) {
            	return $this->error('解压文件失败');
            }
            return $this->success('上传文件并解压成功');
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-05-11
     * 对指定zip文件进行在线解压
     */
    public function UnzipZip($fileName,$path,$name)
    {
    	$zip = new \ZipArchive;
        $hostdir = 'storage/uploads/helix_saga/pic';//解压的图片目录
    	$bool = $zip->open($fileName);
    	$res = $zip->extractTo($hostdir.'/'.$name);
        // $bool= Storage::disk('public')->put('helix_saga/pic/',file_get_contents('storage/uploads/helix_saga/pic/'));
        $filesnames = scandir($hostdir.'/'.$name);

         foreach ($filesnames as $key => $value) {
             if ($value == '.' || $value == '..' || $value == '__MACOSX') {
                 continue;
             }
              $bool= Storage::disk('cosv5')->put('helix_saga/'.$path.'/'.$value,file_get_contents($hostdir.'/'.$name.'/'.$value));
         }
        Storage::disk('public')->deleteDirectory('/uploads/helix_saga/pic');
        Storage::disk('public')->deleteDirectory('/uploads/helix_saga/zip');
    	return $res;
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-05-11
     * @上传图片到腾讯云cos
     */
    public function uploadPic(Request $request)
    {
       $picCount =  $request->post('picCount');//统计上传图片数量
       $error = [];//统计上传文件的错误

       if (intval($picCount) > 20) {
         return $this->error('上传图片数量超过20');
       }

       if (is_null($pic = $request->file('pic'))) {
           return $this->error('请选择图片上传');
       }

       if (empty($path = $request->path)) {
           return $this->error('请选择图片目录');
       }

        foreach ($pic as $k => $v) {
            $fileType = ['image/jpeg','image/png','image/jpg'];
            // 得到上传文件源文件名
            $originalName = $v->getClientOriginalName();

            // 得到上传文件的后缀
            $ext = $v->getClientOriginalExtension();

            // 等到上传文件的类型
            $type = $v->getClientMimeType();

            // 得到上传文件的tmp绝对路径
            $realPath = $v->getRealPath();;

            if (!in_array($type, $fileType)) {
               array_push($error, $originalName.'图片上传格式不正确');
               continue;
            }
             if ($v->getSize() > 30000000) {
                array_push($error, $originalName.'上传文件过大');
               continue;
            }
            Storage::disk('cosv5')->put('helix_saga/'.$path.'/'.$originalName,file_get_contents($realPath));
        }

        if (empty($error)) {
            return $this->success('上传图片成功');
        }else{
            $message =  '';
            foreach ($error as $key => $value) {
                $message .= $value;
            }
            return $this->error('部分图片上传成功'.$message);
        }

           
    }
}
