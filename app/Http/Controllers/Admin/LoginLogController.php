<?php

namespace App\Http\Controllers\Admin;

use App\Models\LoginLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\CommonController;

class LoginLogController extends Controller
{
	/**
	 * @Author    linyiyuan
	 * @DateTime  2018-05-17
	 * @显示出操作日志记录
	 */
    public function getList(Request $request)
    {
    	$where =  [];

    	if (strlen($request->created_at) > 0) {
    		$where[] = ['created_at','like','%'.$request->created_at.'%'];
    	}

    	$loginLog = LoginLog::orderBy('id','desc')
    						->where($where)
    						->paginate(20);

    	return view('admin.index.login_log',compact('loginLog'));
    }
}
