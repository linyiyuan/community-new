<?php

namespace App\Http\Controllers\Api\HelixSaga;

use Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Admin\HelixSaga\DataProcessingController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;

class ToolController extends BaseController
{
	/**
     * @Author    linyiyuan
     * @DateTime  2018-05-16
     * @处理得到道具数据
     */
    public function getList()
    {
    	$redis = Redis::connection('ddz_web');
         // 判断Redis缓存中是否存在
         if ($dataArray = $redis->hget('helix_saga_tool','helix_saga_tool')){
            $dataArray = json_decode($dataArray,true);
            return $this->successReturn(200,$dataArray);
         }else{
            // 重新加载缓存
            $obj = new DataProcessingController();
            $list = $obj->getToolList();
            return $this->successReturn(200,json_decode($list));
         }
         
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-06-19
     * @得到道具详情
     */
    public function getToolDetail($id)
    {
        if (!intval($id)) {
         return $this->errorReturn(500,'非法参数');
        }

        //查看道具详情缓存是否存在，如果存在则获取
        $redis = Redis::connection('ddz_web');
        if ($toolList=$redis->hget('helix_saga_tool','helix_saga_tool')) {
            $toolList = json_decode($toolList,true);
        }else{
            // 重新加载缓存
            $obj = new DataProcessingController();
            $toolList = json_decode($obj->getToolList(),true);
        }

            $toolListArr = [];
            foreach ($toolList as $key => $value) {
                foreach ($value['list'] as $k => $v) {
                    $toolListArr[$v['id']] = $v;
                }
            }

            //获取道具所有id
            $toolListKeys = array_keys($toolListArr);

            //判断id是否在道具表中
            if (!in_array($id,$toolListKeys)) {
                return $this->errorReturn(500,'不存在该角色的数据');
            }

            $toolDetail = $toolListArr[$id];

            return $this->successReturn(200,$toolDetail);
    }
}