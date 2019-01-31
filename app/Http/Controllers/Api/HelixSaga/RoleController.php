<?php

namespace App\Http\Controllers\Api\HelixSaga;

use Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Admin\HelixSaga\DataProcessingController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;

class RoleController extends BaseController
{

    /**
     * @Author    linyiyuan
     * @DateTime  2018-05-16
     * @处理得到角色数据
     */
    public function getList()
    {   
         $redis = Redis::connection('ddz_web');
         // 判断Redis缓存中是否存在
    	 if ($dataArray = $redis->hget('helix_saga_role','helix_saga_role')){
    	 	$dataArray = json_decode($dataArray,true);
    	 	return $this->successReturn(200,$dataArray);
    	 }else{
            // 重新加载缓存
            $obj = new DataProcessingController();
            $list = $obj->getRoleList();
            return $this->successReturn(200,json_decode($list));
         }
	    
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-06-19
     * @处理得到角色详情
     */
    public function getRoleDetail($id)
    {
       if (!intval($id)) {
            return $this->errorReturn(500,'非法参数');
        }

         $redis = Redis::connection('ddz_web');
         // 判断Redis缓存中是否存在
         if ($dataArray = $redis->hget('helix_saga_role','helix_saga_role')){
            $roleList = json_decode($dataArray,true);
         }else{
            // 重新加载缓存
            $obj = new DataProcessingController();
            $roleList = json_decode($obj->getRoleList(),true);
         }

         //获取角色技能
         if ($skillList = $redis->hget('helix_saga_skill','helix_saga_skill')) {
             $skillList = json_decode($skillList,true);
         }else{
             $obj = new DataProcessingController();
             $skillList = json_decode($obj->getRoleList(),true);
         }

         $roleListArr = [];
            foreach ($roleList as $key => $value) {
                foreach ($value['list'] as $k => $v) {
                    $roleListArr[$v['id']] = $v;
                }
            }
            
             //获取角色所有id
            $roleListKeys = array_keys($roleListArr);

            //判断id是否在角色表中
            if (!in_array($id,$roleListKeys)) {
                return $this->errorReturn(500,'不存在该角色的数据');
            }   

            $roleDetail = $roleListArr[$id];

            //处理得到角色名字对应相应的技能
            $skillListArr = [];
            foreach ($skillList as $key => $value) {
                if (!is_null($value['skill'])) {
                   $skillListArr[$value['name']][] = $value;
                }
            }
            $roleDetail['role_skill'] = $skillListArr[$roleDetail['name']];

            return $this->successReturn(200,$roleDetail);
    }
}
