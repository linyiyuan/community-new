<?php

namespace App\Http\Controllers\Api\HelixSaga;

use Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Api\HelixSaga\RoleController;
use App\Http\Controllers\Api\HelixSaga\ToolController;

/**
 * 螺旋英雄谭图鉴搜索
 */
class BlendSearchController extends BaseController
{
	/**
	 * @Author    linyiyuan
	 * @DateTime  2018-06-08
	 * 获取图鉴搜索结果
	 * @return    [type]      [description]
	 */
    public function getSearchRes(Request $request)
    {
        $keywords = $request->keywords;
    	if (!isset($keywords)) {
    		return $this->errorReturn(500,'非法参数');
    	}

    	if (strlen($keywords) < 0) {
            return $this->successReturn(200,[]);
        }
         $redis = Redis::connection('ddz_web');

        //从缓存获取角色表数据
        $roleList = $redis->hget('helix_saga_role','helix_saga_role');
        $roleList = json_decode($roleList,true);

        //从缓存中获取道具表数据
        $toolList = $redis->hget('helix_saga_tool','helix_saga_tool');
        $toolList = json_decode($toolList,true);

        //从缓存中获取技能表数据
        $skillList = $redis->hget('helix_saga_skill','helix_saga_skill');
        $skillList = json_decode($skillList,true);
        
        // return $this->successReturn(200,$skillList);
         $roleList = array_column($roleList, 'list');
         $toolList = array_column($toolList, 'list');
        
         //处理得到所有道具跟角色，技能名称对应的id跟图片
         $totalArr = [];
         foreach ($roleList as $key => $value) {
             foreach ($value as $k => $v) {
                 $totalArr[$v['name']]['id'] = $v['id'];
                 $totalArr[$v['name']]['avatar'] = $v['avatar'];
                 $totalArr[$v['name']]['name'] = $v['name'];
                 $totalArr[$v['name']]['type'] = 'role';
             }
         }  
         // return $this->successReturn(200,$toolList);

         foreach ($toolList as $key => $value) {
             foreach ($value as $k => $v) {
                 $totalArr[$v['prop']]['id'] = $v['id'];
                 $totalArr[$v['prop']]['img'] = $v['img'];
                 $totalArr[$v['prop']]['name'] = $v['prop'];
                 $totalArr[$v['prop']]['type'] = 'tool';
             }
         }  

         foreach ($skillList as $key => $value) {
             $totalArr[$value['skill']]['id'] = $value['id'];
             $totalArr[$value['skill']]['name'] = $value['name'];
             $totalArr[$value['skill']]['type'] = $value['type'];
             $totalArr[$value['skill']]['desc'] = $value['desc'];
             $totalArr[$value['skill']]['img'] = $value['img'];
             $totalArr[$value['skill']]['type'] = 'skill';
             
         }
         $totalNameList = array_keys($totalArr);
         //用来储存模糊匹配到的角色名称
         $searchKeys = [];
         foreach ($totalNameList as $key => $value) {
             if (strstr($value,$keywords) !== false) {
                 array_push($searchKeys, $value);
             }
         }

         //用来存储搜索的结果
         $searchRes = [];
         foreach ($searchKeys as $key => $val) {
             $searchRes[] = $totalArr[$val];
         }


         return $this->successReturn(200,$searchRes);

    }
}
