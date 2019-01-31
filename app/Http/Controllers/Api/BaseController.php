<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class BaseController extends Controller
{
	/**
	 * @Author    linyiyuan
	 * @DateTime  2018-04-16
	 * @处理url路径
	 * @param     [type]      $url [description]
	 */
    public function getFullUrl($url)
    {
        if (!$url) {
            return '';
        }
        if (strtolower(substr($url, 0, 4)) == 'http') {
            return $url;
        }
        $cdn = \Config::get('app.cdn_url');
        if (strtolower(substr($cdn, 0, 4)) == 'http') {
            return $cdn . $url;
        }
        return url($url);
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

    /**
     * @Author    linyiyuan
     * @DateTime  2018-04-16
     * 对象转为数组
     */
    public function toArray($data = '')
    {
        return  json_decode(json_encode($data),true);
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-04-17
     * @得到当前日期的零点时间
     */
    public function getNowTime()
    {
         $todayTime = date('Y-m-d',time());//获取当天时间
         $todayTime = strtotime($todayTime);

         return $todayTime;
    }

     /**
     * @Author    linyiyuan
     * @DateTime  2018-03-30
     * 处理富文本编辑的标签
     * @return    [type]      [description]
     */
    public function getUeditorData($content)
    {
        //去掉宽度
        $width = '/(<img.*?)width=(["\'])?.*?(?(2)\2|\s)([^>]+>)/is';
        $content = preg_replace($width,'$1$3',$content);

        // 去掉高度
        $height = '/(<img.*?)height=(["\'])?.*?(?(2)\2|\s)([^>]+>)/is';
        $content = preg_replace($height,'$1$3',$content);

        // 去掉样式
        $style = '/(<img.*?)style=(["\'])?.*?(?(2)\2|\s)([^>]+>)/is';
        $content =  preg_replace($style,'$1$3',$content);

        //更改url
        // if ($_SERVER["HTTP_HOST"] == 'api.nba.dasheng.tv') {
        //     $arr = [
        //         '211.159.184.31',
        //         '139.199.0.227',
        //     ]; 

        //     // $url = 'http://'.$_SERVER["HTTP_HOST"];//正式环境
        //     preg_match_all('/\/ueditor.*?(jpg|jpeg|gif|png)/', $content, $m);
        //     foreach ($m[0] as $key => $value) {
        //        $url = $this->checkUrl($arr,$value);
        //        $content = str_replace($value, $url, $content);
        //     }
            
        //     return $content;
        // }else{
        //     //更改url
        //     $url = 'http://'.$_SERVER["HTTP_HOST"];//正式环境
        //     $content = str_replace('/ueditor', $url.'/ueditor', $content);
            return $content;
        // }
        
    }


    public function checkUrl($arr,$img){
           foreach ($arr as $key => $value) {
               $url = 'http://'.$value.':8099'.$img;
               if (@file_get_contents($url)) {
                  return $url;
               }
           }
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-06-06
     * 用来判断远程文件是否存在
     */
    public function getUrlExists($url){
        if (!isset($url)) {
            return '请输入要验证的url';
        }
        $ch = curl_init(); 
        $timeout = 10; 
        curl_setopt ($ch, CURLOPT_URL, $url); 
        curl_setopt ($ch, CURLOPT_HEADER, 1); 
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 
        $contents = curl_exec($ch);
        if(preg_match("/404/", $contents)){
            return false;
        }else{
            return true;
        }
    }

}
