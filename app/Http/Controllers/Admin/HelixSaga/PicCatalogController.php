<?php

namespace App\Http\Controllers\Admin\HelixSaga;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HelixSaga\PicCatalog;
use App\Http\Controllers\Base\CommonController;

/**
 * 螺旋管英雄谭图片目录管理
 */
class PicCatalogController extends CommonController
{
    /**
     * 显示图片目录列表
     */
    public function index()
    {
        $list  = PicCatalog::orderBy('id','desc')
        				   ->paginate(10);
        return view('admin.helixSaga.pic_catalog.index',compact('list'));
    }

    /**
     * 图片目录新建页面
     */
    public function create()
    {
    	$picCatalog = new PicCatalog(); 
        return view('admin.helixSaga.pic_catalog.edit',compact('picCatalog'));
    }

    /**
     * 对图片目录进行添加操作
     */
    public function store(Request $request) 
    {
         $this->validate($request,[
            'path' => 'required',
        ]);

         $picCatalog = new PicCatalog();
         $picCatalog->path = $request->path;
         $picCatalog->desc = $request->desc?$request->desc:'无任何描述';

         if (!$picCatalog->save()) {
         	return $this->error('添加图片目录失败');
         }

         return $this->success('添加图片目录成功');
    }


    /**
     * 显示图片目录修改页面
     */
    public function edit($id)
    {
        if (!intval($id)) {
        	return $this->error('非法参数');
        }

        if (is_null($picCatalog=PicCatalog::find($id))) {
        	return $this->error('获取数据失败');
        }

        return view('admin.helixSaga.pic_catalog.edit',compact('picCatalog'));
    }

    /**
     * 对图片目录进行修改
     */
    public function update(Request $request, $id)
    {
    	 if (!intval($id)) {
        	return $this->error('非法参数');
         }
         
         $this->validate($request,[
            'path' => 'required',
         ]);

        if (is_null($picCatalog=PicCatalog::find($id))) {
        	return $this->error('获取数据失败');
        }

         $picCatalog->path = $request->path;
         $picCatalog->desc = $request->desc?$request->desc:'无任何描述';

         if (!$picCatalog->save()) {
         	return $this->error('修改图片目录失败');
         }

         return $this->success('修改图片目录成功');
    }

}
