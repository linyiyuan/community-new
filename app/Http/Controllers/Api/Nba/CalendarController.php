<?php

namespace App\Http\Controllers\Api\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Nba\ColumnController;
use App\Models\Nba\Calendar;


class CalendarController extends BaseController
{
     /**
     * @Author    linyiyuan
     * @DateTime  2018-04-16
     * @获取老黄历
     * @license   [license]
     * @version   [version]
     * @return    [type]      [description]
     */
     protected function getCalendarDetail()
    {
    	$todayTime = date('Y-m-d',time());//获取当天时间
    	$todayTime = strtotime($todayTime);

    	$where = [['time','=',$todayTime]];

    	$calendar = Calendar::where($where)
    						->first();

    	if (is_null($calendar)) {
    		return [];
    	}

    	$data['id'] = $calendar->id;

        //将宜内容转换成带p标签的数组
        $good_content = explode(',', $calendar->good_content);
        $good_content = array_map(function($val){ return '<p>'.$val.'</p>';}, $good_content);

        //将忌内容转换成带p标签的数组
        $bad_content = explode(',', $calendar->bad_content);
        $bad_content = array_map(function($val){ return '<p>'.$val.'</p>';}, $bad_content);

    	$data['good_content'] = $good_content;
    	$data['bad_content'] = $bad_content;
    	$data['person_name'] = $calendar->person_name;
    	$data['person_word'] = $calendar->person_word;
    	$data['avatar'] = $this->getFullUrl($calendar->avatar);
    	$data['is_show'] = $calendar->is_show;
    	$data['tip'] = $calendar->tip;
        $data['time'] = $calendar->time;
    	$data['img'] = $this->getFullUrl($calendar->img);
    	$data['created_at'] = $calendar->created_at->format('Y-m-d');
    	$data['updated_at'] = $calendar->updated_at->format('Y-m-d');

    	return $this->successReturn(200,$data);
    }
}
