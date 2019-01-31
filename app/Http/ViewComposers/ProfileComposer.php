<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;


class ProfileComposer
{

    /**
    * 绑定数据到视图.
    *
    * @param View $view
    * @return void
    */
    public function compose(View $view)
    {
        $menu = [

            //全局配置
            ['icon' => 'am-icon-table' ,'type' => '全局配置','url' => '/config','data' => [
                ['type' => '社区列表', 'url' => '/config/community'],
            ] ],

            //NBA社区
            ['icon' => 'am-icon-dribbble' ,'type' => '最强NBA','url' => '/nba','data' => [
                ['type' => '首页栏目管理', 'url' => '/nba/nba_column'],
                ['type' => '轮播管理', 'url' => '/nba/nba_carousel'],
                ['type' => '老黄历管理', 'url' => '/nba/nba_calendar'],
                ['type' => '视频管理', 'url' => '/nba/nba_video'],
                ['type' => '漫画管理', 'url' => '/nba/nba_manga'],
                ['type' => '图文管理', 'url' => '/nba/nba_content'],
            ] ],

             //螺旋英雄谭
            ['icon' => 'am-icon-pie-chart' ,'type' => '螺旋英雄谭','url' => '/helix_saga','data' => [
                ['type' => '数据接口管理', 'url' => '/helix_saga/data_dictionary'],
                ['type' => '批量导入图片', 'url' => '/helix_saga/picture'],
                ['type' => '图片目录管理', 'url' => '/helix_saga/pic_catalog'],
            ] ],

            //  //数据采集功能
            // ['icon' => 'am-icon-download' ,'type' => '数据采集','data' => [
            //     ['type' => '秒拍数据采集', 'url' => '/data_acquisition/miaopai'],
            // ] ],

             //用户管理
            ['icon' => 'am-icon-user' ,'type' => '后台管理','url' => '/admin','data' => [
                ['type' => '管理员列表', 'url' => '/admin/user'],
                ['type' => '角色管理', 'url' => '/admin/role'],
                ['type' => '系统配置', 'url' => '/admin/system'],
                ['type' => '操作日志', 'url' => '/admin/log'],
            ] ],
        ];

        $view->with('menu', $menu);

       
    }
}