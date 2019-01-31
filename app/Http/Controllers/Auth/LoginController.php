<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginLog;
use App\Http\Controllers\Base\CommonController;
use Session;
use App\User;
use App\Role;

class LoginController extends CommonController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '';
    
    protected function redirectTo()
    {
        //获取用户角色
        $user = User::find(Auth::user()->id);

        $str = '';
        foreach ($user->roles as $role) {
           $str .= $role->display_name.',';
        }

        //记录登录日志报告
        $loginLog = new LoginLog();//获取登录日志对象
        $loginLog->username = Auth::user()->name;
        $loginLog->role = rtrim($str,',');
        $loginLog->ip = $this->getIp();
        $loginLog->result = 1;
        $loginLog->operate = '用户登录操作';
        $loginLog->detail = Auth::user()->name.'在'.date('Y-m-d H:i:s',time()).'登录后台管理系统';
        
        if ($loginLog->save()) {
             return '/index';
        }else{
            Session::flash('message', ['code' => 500, 'data' => '记录登录日志失败']);
            return '/index';
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-05-17
     * 获取客户端IP
     */
    public function  getIp(){
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
        else
        $ip = "unknown";
        return($ip);
    }

}
