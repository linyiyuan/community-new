<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\CommonController;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Permission;
use DB;
use Illuminate\Support\Facades\Route;


class UserController extends CommonController
{
    /**
     * 
     *后台用户列表页
     * @return \Illuminate\Http\view
     */
    public function index(Request $request)
    {
        $where = [];
        if (strlen($name = $request->input('name')) > 0 ) {
            $where = [ ['name','like','%'.$name.'%'] ];
        }
        $list = User::where($where)->paginate(5);

        if ($list->isEmpty()) {
            $list = '';
        }

        return view('admin.user.list',['list' => $list,'name' => $name]);
    }

    /**
     *显示用户添加页面
     */
    public function create()
    {   
        $user = new User();

        $role = Role::all();

        return view('admin.user.edit',compact('user','role')); 
    }

    /**
     * 对用户进行添加
     */
    public function store(Request $request)
    {
         $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required'
        ]);

        $user = new User();


        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));

        if ($request->file('img')) {
            try {
                if (!$path = $this->uploadImageData('img',['png','jpeg','jpg'],'uploads/user')) {
                   return $this->error('图片保存失败');
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            $user->img = $path;
        }

        if (!$user->save()) {
            $this->recordLog('用户添加操作','添加一个新的用户',0);
            return $this->error('添加用户失败');
        }   

        foreach ($request->input('role') as $key => $value) {
             $user->attachRole($value);
        }
       
        $this->recordLog('用户添加操作','添加一个新的用户',1);//记录日志
        return $this->success('添加用户成功');
        
    }

    /**
     * 用户状态操作
     */
    public function show(Request $request,$id)
    {
        $status = $request->input('status');

        if ($id == Auth::id()) {
            return $this->ajaxResponse('500','无法修改当前登录用户状态');
        }

        if(!intval($id) && is_null($status) ){
            return $this->ajaxResponse('500','非法参数');
        }
        
        if ($status == 1) {
            $updateRes = User::where('id',$id)->update(['status' => 0]);
        }elseif ($status == 0) {
            $updateRes = User::where('id',$id)->update(['status' => 1]);
        }
        if ($updateRes) {

            return $this->ajaxResponse('200','修改成功');
        }else{

            return $this->ajaxResponse('500','修改失败');
        }

    }

    /**
     * 修改用户页面
     */
    public function edit($id)
    {
        
        if (!intval($id)) {
            return $this->error('非法参数');
        }

        $user = User::find($id);
       
        if (is_null($user)) {
            return $this->error('获取数据失败，请检查是否存在该数据');
        }  

        $role = Role::all();

        return view('admin.user.edit',compact('user','role'));

    }

    /**
     * 对用户信息进行修改
     */
    public function update(Request $request, $id)
    {
        if (!intval($id)) {
            return $this->error('非法参数');
        }

        $user = User::find($id);

        if (is_null($user)) {
            return $this->error('获取数据失败');
        }

        if (is_null($request->input('password'))) {

            $this->validate($request, [
                'name' => 'required|string|max:255',
                'role'     => 'required'
            ]);

            if (!intval($request->input('role'))) {
               return $this->error('获取角色id失败');
            }

            $user->name = $request->input('name');

            if ($request->file('img')) {
                try {
                    if (!$path = $this->uploadImageData('img',['png','jpeg','jpg'],'uploads/user')) {
                       return $this->error('图片保存失败');
                    }
                    } catch (\Exception $e) {
                        return $this->error($e->getMessage());
                    }
                     $user->img = $path;
             }
            if (!$user->save()) {
                $this->recordLog('用户修改操作','修改邮箱为'.$user->email.'用户',0);//记录到日志
                return $this->error('修改用户信息失败');
            }            


            if($user->roles->isEmpty()){
               foreach ($request->input('role') as $key => $value) {
                        $user->attachRole($value);
                  }
            }else{
               DB::table('role_user')->where('user_id',$id)->delete();
               foreach ($request->input('role') as $key => $value) {
                        $user->attachRole($value);
                  }
            }               

            $this->recordLog('用户修改操作','修改邮箱为'.$user->email.'用户',1);//记录到日志
            return $this->success('修改用户信息成功');
            

        }else{
             $this->validate($request, [
                    'name' => 'required|string|max:255',
                    'password' => 'required|string|min:6|confirmed',
                    'role'     => 'required'
             ]);

            if (!intval($request->input('role'))) {
               return $this->error('获取角色id失败');
            }

            $user->name = $request->input('name');
            $user->password = bcrypt($request->input('password'));

            if ($request->file('img')) {
            try {
                if (!$path = $this->uploadImageData('img',['png','jpeg','jpg'],'uploads/user/')) {
                   return $this->error('图片保存失败');
                }
                } catch (\Exception $e) {
                    return $this->error($e->getMessage());
                }
                 $user->img = $path;
             }
          
            if (!$user->save()) {
                $this->recordLog('用户修改操作','修改邮箱为'.$user->email.'用户',0);//记录到日志
                return $this->error('修改用户失败');
            }

            if($user->roles->isEmpty()){
               foreach ($request->input('role') as $key => $value) {
                        $user->attachRole($value);
                  }
            }else{
               DB::table('role_user')->where('user_id',$id)->delete();
               foreach ($request->input('role') as $key => $value) {
                        $user->attachRole($value);
                  }
            }

            $this->recordLog('用户修改操作','修改邮箱为'.$user->email.'用户',1);//记录到日志
            return $this->success('修改用户信息成功');
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
