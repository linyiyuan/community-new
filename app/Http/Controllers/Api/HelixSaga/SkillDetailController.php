<?php

namespace App\Http\Controllers\Api\HelixSaga;

use Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;

class SkillDetailController extends BaseController
{
    /**
	 * @Author    linyiyuan
	 * @DateTime  2018-06-05
	 * @copyright 处理得到技能详情数据
	 * @param     [integer]      $id [技能id]
	 * @return    [json]             [技能详情]
	 */
    public function getSkillDetail($id)
    {
    	if (!intval($id)) {
    		return $this->errorReturn(500,'非法参数');
    	}

    	$redis = Redis::connection('ddz_web');
    	if ($skillDetail=$redis->hget('helix_saga_skill_detail','helix_saga_skill_detail_'.$id)) {
    		$skillDetail = json_decode($skillDetail,true);
    	 	return $this->successReturn(200,$skillDetail);
    	}

         //查看技能列表缓存是否存在,如果存在则获取
        if ($skillList=$redis->hget('helix_saga_skill','helix_saga_skill')) {
            $skillList = json_decode($skillList,true);
            
           	$ids = array_column($skillList, 'id');

           	$skillListArr = array_combine($ids, $skillList);

            //判断id是否在技能表中
            if (!in_array($id,$ids)) {
                return $this->errorReturn(500,'不存在该技能的数据');
            }

            if(!empty($skillDetail = $skillListArr[$id])){
                //加入缓存
                $redis = Redis::connection('ddz_web_m');
                $redis->hset('helix_saga_skill_detail','helix_saga_skill_detail_'.$id,json_encode($skillDetail));

                return $this->successReturn(200,$skillDetail);
            }

            return [];
            
        }

    	$phpExcel = new Excel();

    	 //得到腾讯云上的技能表
         $filename = env('COSV5_CDN').'helix_haga/dataDictionary/skill.xlsx';

         if (!$this->getUrlExists($filename)) {
            return $this->errorReturn(500,'获取Excel文件失败');
         }
         
         $dir = 'helix_saga/skill.xlsx';
         $bool = Storage::disk('public')->put($dir, file_get_contents($filename));

         if (!$bool) {
             return errorReturn(500,'下载Excel文件出错');
         }

         $filename = 'storage/'.$dir;

         $PHPReader = new \PHPExcel_Reader_Excel2007();

         $PHPExcel = $PHPReader->load($filename);

         $skillSheet = $PHPExcel->getSheet(0);//拿到Excel表中第一张sheet(技能)表 

         //处理得到技能数据数组
         $skillHighestRow = $skillSheet->getHighestRow();
         $skillArray = $skillSheet->toArray();

         //拿到技能数据表中的表头
         $skillSheetHeader = $skillArray[0];

         unset($skillArray[0]);

         // 处理得到id对应着相应的技能数据
         $skillList = [];
         foreach ($skillArray as $key => $value) {
         	$values = array_combine($skillSheetHeader, $value);
         	$skillList[$value[0]] = $values; 
         }

         //拿到技能数据表中存在的所有id
         $skillListKeys = array_keys($skillList);

         if (!in_array($id,$skillListKeys)) {
         	return $this->errorReturn(500,'不存在该技能的数据');
         }

   		if (!empty($skillDetail=$skillList[$id])) {

   			$redis = Redis::connection('ddz_web_m');
   			$redis->hset('helix_saga_skill_detail','helix_saga_skill_detail_'.$id,json_encode($skillDetail));

   			return $this->successReturn(200,$skillDetail);
   		}
         
   		return [];
    }
}
