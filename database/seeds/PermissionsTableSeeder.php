<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	foreach ($this->getPermissionsData() as $key => $value) {
             $permission = new Permission();
             $permission->name = $value['name'];
             $permission->display_name = $value['display_name'];
             $permission->description = $value['description'];
             $permission->type = $value['type'];
             $permissionExist = Permission::where('name',$value['name'])->first();
             if (!$permissionExist) {
                echo '添加新的一条新权限'.'....................'.$value['display_name'].PHP_EOL;
                $permission->save();
             }else{
                echo $value['display_name'].'....................该权限已经存在'.PHP_EOL;
             }
             
    	}
      
    }

    protected function getPermissionsData()
    {
    	$data = [
            //最强NBA社区
    		[ 'name' => 'CarouselsController' , 'display_name' => 'NBA轮播图管理', 'description' => '可以对NBA社区进行轮播图管理操作' ,'type' => '最强NBA社区' ],
    		[ 'name' => 'CalendarController' , 'display_name' => 'NBA老黄历管理', 'description' => '可以对NBA社区进行老黄历管理操作' ,'type' => '最强NBA社区' ],
    		[ 'name' => 'VideoController' , 'display_name' => 'NBA视频管理', 'description' => '可以对NBA社区进行视屏管理' ,'type' => '最强NBA社区'],
    		[ 'name' => 'MangaController' , 'display_name' => 'NBA漫画管理', 'description' => '可以对NBA社区漫画进行管理操作' ,'type' => '最强NBA社区'],
    		[ 'name' => 'ContentController' , 'display_name' => 'NBA图文管理', 'description' => '可以对NBA社区图文进行管理操作','type' => '最强NBA社区' ],
    		[ 'name' => 'VideoTagController' , 'display_name' => 'NBA视频标签管理', 'description' => '可以对NBA社区进行视频标签管理','type' => '最强NBA社区' ],
    		[ 'name' => 'MangaTagController' , 'display_name' => 'NBA漫画标签管理', 'description' => '可以对NBA漫画标签进行管理','type' => '最强NBA社区' ],
            [ 'name' => 'VideoListController' , 'display_name' => 'NBA视频集合管理', 'description' => '可以对NBA视频集合进行管理','type' => '最强NBA社区' ],
    		[ 'name' => 'VideoListContentController' , 'display_name' => 'NBA视频集合内容管理', 'description' => '可以对NBA视频集合内容管理','type' => '最强NBA社区' ],
    		[ 'name' => 'ContentTagController' , 'display_name' => 'NBA图文标签管理', 'description' => '可以对图文标签进行管理','type' => '最强NBA社区' ],
            [ 'name' => 'ColumnController' , 'display_name' => 'NBA首页栏目管理', 'description' => 'Nba首页栏目管理','type' => '最强NBA社区' ],

            //螺旋英雄谭
            ['name' => 'DataDictionaryController','display_name' => '数据接口管理','description' => '可以对螺旋英雄谭数据接口进行管理','type' => '螺旋英雄谭'],
            ['name' => 'ImportPictureController','display_name' => '批量导入数据','description' => '可以对螺旋英雄谭批量导入数据进行管理','type' => '螺旋英雄谭'],
            ['name' => 'PicCatalogController','display_name' => '图片目录管理','description' => '可以对螺旋英雄谭图片目录管理进行管理','type' => '螺旋英雄谭'],

    	];

        return $data;
    }
}