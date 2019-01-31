<?php

namespace App\Http\Controllers\Api\HelixSaga;

use Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;

/**
 * 螺旋英雄谭技能接口
 */
class SkillController extends BaseController
{
    /**
     * @Author    linyiyuan
     * @DateTime  2018-06-05
     * @copyright 处理得到技能数据
     * @return    [json]      [技能列表]
     */
    public function getList()
    {
    	 $redis = Redis::connection('ddz_web');
         // 判断Redis缓存中是否存在
         if ($dataArray = $redis->hget('helix_saga_skill','helix_saga_skill')){
            $dataArray = json_decode($dataArray,true);
            return $this->successReturn(200,$dataArray);
         }else{
            // 重新加载缓存
            $obj = new DataProcessingController();
            $list = $obj->getskillList();
            return $this->successReturn(200,json_decode($list));
         }

    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-06-19
     * @得到技能详情
     */
    public function getSkillDetail($id)
    {
        if (!intval($id)) {
            return $this->errorReturn(500,'非法参数');
        }

         $redis = Redis::connection('ddz_web');
         // 判断Redis缓存中是否存在
         if ($dataArray = $redis->hget('helix_saga_skill','helix_saga_skill')){
            $skillList = json_decode($dataArray,true);
         }else{
            // 重新加载缓存
            $obj = new DataProcessingController();
            $skillList = json_decode($obj->getskillList(),true);
         }

         //拿到技能表所有存在id
         $skillListKeys = array_column($skillList, 'id');
         //处理得到技能id对应相应的详情
         $skillList = array_combine($skillListKeys, $skillList);

        if (!in_array($id,$skillListKeys)) {
            return $this->errorReturn(500,'不存在该角色的数据');
        }   

        return $this->successReturn(200,$skillList[$id]);

    }

}
