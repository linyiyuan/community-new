<?php

namespace App\Http\Controllers\Admin\HelixSaga;

use Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\CommonController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;

class DataProcessingController extends CommonController
{
	/**
	 * @Author    linyiyuan
	 * @DateTime  2018-06-19
	 * 得到螺旋英雄谭角色数据
	 */
    public function getRoleList()
    {
        $redis = Redis::connection('ddz_web_m');
         // 判断Redis缓存中是否存在,如果有的话则将它删除
         if ($dataArray = $redis->hget('helix_saga_role','helix_saga_role')){
           $redis->del('helix_saga_role');
        }

    	$phpExcel = new \PHPExcel();
      
         //得到腾讯云上的角色表
         $filename = env('COSV5_CDN').'helix_haga/dataDictionary/role.xlsx';
           
         if (!$this->getUrlExists($filename)) {
            return $this->errorReturn(500,'获取Excel文件失败');
         }
         $dir = 'helix_saga/role.xlsx';
         $bool = Storage::disk('public')->put($dir, file_get_contents($filename));

         if (!$bool) {
             return errorReturn(500,'下载Excel文件出错');
         }

         $filename = 'storage/'.$dir;

         $PHPReader = new \PHPExcel_Reader_Excel2007();

         $PHPExcel = $PHPReader->load($filename);

         $roleSheet = $PHPExcel->getSheet(0);//拿到Excel表中第一张sheet(角色)表 

         $propertySheet = $PHPExcel->getSheet(1);//拿到Excel表中第二张sheet(属性)表 


         //处理role角色表,得到全部role的数据
         $roleHighestRow = $roleSheet->getHighestRow();
         $roleArray = $roleSheet->toArray();

         //处理属性表，得到属性表的数据
         $propertyHighestRow = $propertySheet->getHighestRow();
         $propertyArray = $propertySheet->toArray();


         $roleSheetHeader = $roleArray[0];//拿到角色表的表头

         unset($roleArray[0]);
         unset($propertyArray[0]);


         //处理得到id对应着属性名的数组
         $propertyList = array_combine(array_column($propertyArray, '0'), array_column($propertyArray, '1'));
         //处理得到属性名对应的属性图的数组
     	 $propertyImgList = array_combine(array_column($propertyArray, '1'), array_column($propertyArray, '2'));

     	 $list = [];
         foreach ($propertyList as $key => $value) {
             $list[$value] = [];
         }
         $roleList = [];

         foreach ($roleArray as $key => $value) {

         	$roleList[] = array_combine($roleSheetHeader, $value);

         }

         foreach ($roleList as $k => $v) {
         	$roleList[$k]['attr'] = $propertyList[$v['attr']];
         }

         foreach ($roleList as $key => $value) {
            $list[$value['attr']]['id'] = array_flip($propertyList)[$value['attr']];
            $list[$value['attr']]['attribute'] = $propertyList[array_flip($propertyList)[$value['attr']]];
            $list[$value['attr']]['img'] = $propertyImgList[$value['attr']];
            $list[$value['attr']]['list'][] = $value;
         }

        //去除数组中存在的空值
        $list = array_filter($list); 

        // 存入Redis缓存中
        $redis = Redis::connection('ddz_web_m');
        $redis->hset('helix_saga_role','helix_saga_role',json_encode($list));

	    return json_encode($list);
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-06-19
     * @处理得到螺旋英雄谭道具列表
     */
    public function getToolList()
    {
        $redis = Redis::connection('ddz_web_m');
         // 判断Redis缓存中是否存在
         if ($dataArray = $redis->hget('helix_saga_tool','helix_saga_tool')){
            $redis->del('helix_saga_tool');
         }

         $phpExcel = new \PHPExcel();
         
         //得到腾讯云上的道具表
         $filename = env('COSV5_CDN').'helix_haga/dataDictionary/tool.xlsx';

         //判断远程文件是否存在
         if (!$this->getUrlExists($filename)) {
            return $this->errorReturn(500,'获取Excel文件失败');
         }

         $dir = 'helix_saga/tool.xlsx';
         $bool = Storage::disk('public')->put($dir, file_get_contents($filename));

         if (!$bool) {
             return $this->errorReturn(500,'下载Excel文件出错');
         }

         $filename = 'storage/'.$dir;

         $PHPReader = new \PHPExcel_Reader_Excel2007();

         $PHPExcel = $PHPReader->load($filename);

         $toolSheet = $PHPExcel->getSheet(0);//拿到Excel表中第一张sheet(道具)表 

         $toolTypeSheet = $PHPExcel->getSheet(1);//拿到Excel表中第二张sheet(属性)表 

         //处理tool道具表,得到全部道具的数据
         $toolHighestRow = $toolSheet->getHighestRow();
         $toolArray = $toolSheet->toArray();

         //处理tool道具表，得到属性表的数据
         $toolTypeHighestRow = $toolTypeSheet->getHighestRow();
         $toolTypeArray = $toolTypeSheet->toArray();

         $toolSheetHeader = $toolArray[0];//拿到道具表的表头

         unset($toolArray[0]);
         unset($toolTypeArray[0]);


         //处理得到id对应着属性名的数组
         $toolTypeList = array_combine(array_column($toolTypeArray, '0'), array_column($toolTypeArray, '1'));

         $toolList = [];
         foreach ($toolArray as $key => $value) {
            $toolList[] = array_combine($toolSheetHeader, $value);
         }


         //替换道具列表里面的道具类型为对应道具类型名
         foreach ($toolList as $k => $v) {
            $toolList[$k]['type'] = $toolTypeList[$v['type']];
         }

        //得到每个道具类型对应的道具列表 
        $list = [];
        foreach ($toolTypeList as $key => $value) {
             $list[$value] = [];
         } 
        foreach ($toolList as $key => $value) {
            $list[$value['type']]['id'] = array_flip($toolTypeList)[$value['type']];
            $list[$value['type']]['type'] = $value['type'];
            $list[$value['type']]['list'][] = $value;
         }
        
         // 存入Redis缓存中
        $redis = Redis::connection('ddz_web_m');
        $redis->hset('helix_saga_tool','helix_saga_tool',json_encode($list));

        return json_encode($list);
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-06-19
     * @处理得到螺旋英雄谭
     * @license   [license]
     * @version   [version]
     * @return    [type]      [description]
     */
    public function getSkillList()
    {
        //判断Redis缓存中是否存在
         $redis = Redis::connection('ddz_web_m');
         if ($dataArray = $redis->hget('helix_saga_skill','helix_saga_skill')){
            $redis->del('helix_saga_skill');
         }

         $phpExcel = new \PHPExcel();
         
         //得到腾讯云上的道具表
         $filename = env('COSV5_CDN').'helix_haga/dataDictionary/skill.xlsx';

         if (!$this->getUrlExists($filename)) {
            return $this->errorReturn(500,'获取Excel文件失败');
         }
         
         $dir = 'helix_saga/skill.xlsx';
         $bool = Storage::disk('public')->put($dir, file_get_contents($filename));

         if (!$bool) {
             return $this->errorReturn(500,'下载Excel文件出错');
         }

         $filename = 'storage/'.$dir;

         $PHPReader = new \PHPExcel_Reader_Excel2007();

         $PHPExcel = $PHPReader->load($filename);

         $skillArray = $PHPExcel->getSheet(0)->toArray();//拿到Excel表中第一张sheet(道具)表 

         $skillHeader = $skillArray[0];//拿到表头

         unset($skillArray[0]);

         $list = [];

         foreach ($skillArray as $key => $value) {
            $list[] = array_combine($skillHeader, $value);
         }

         //加入缓存
         $redis = Redis::connection('ddz_web_m');
         $redis->hset('helix_saga_skill','helix_saga_skill',json_encode($list));

         return json_encode($list);
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-04-16
     * @成功时ajax响应
     */
    protected function successReturn($code,$data)
    {
        return response()->json([
                'code' => 200,
                'msg'  => 'success',
                'data' => $data,
            ]);
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-04-16
     * @失败时响应
     */
    protected function errorReturn($code,$data)
    {

        return response()->json([
            'code' => $code,
            'msg'  => 'error',
            'data' => $data,

        ]);
    }
}
