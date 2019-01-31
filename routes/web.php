<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');


Route::namespace('Admin')->group(function(){
			
			Route::group(['middleware' => ['auth']], function () {
					//后台首页
					Route::resource('/index','IndexController');
					
				    Route::prefix('/admin')->group(function(){
					   	//系统信息
					   	Route::get('/system','SystemController@index');

					   	Route::group(['middleware' => ['role:super_admin']] ,function() {
						   	//后台用户管理页
						   	Route::resource('/user','UserController');
						   	//后台角色管理
						   	Route::resource('/role','RoleController');
						   	//后台权限管理
						   	Route::resource('/permission','PermissionController');
						   	//后台日志查看
						   	Route::get('/log','LoginLogController@getList');
					   	});
					});

					//全局配置
					Route::group(['prefix' => '/config'],function(){
						//社区列表
					   	Route::resource('/community','CommunityController');
					});

			   		//最强nba路由管理
				   	Route::group(['prefix' => '/nba','namespace' => 'Nba'],function(){
				   		//需要权限访问的路由
				   		Route::group(['middleware' => ['checkPermission']] ,function() {
				   			//最强首页栏目
				   			Route::resource('/nba_column','ColumnController');
				   			//最强NBA轮播图
				   			Route::resource('/nba_carousel','CarouselsController');
				   			//最强NBA老黄历
				   			Route::resource('/nba_calendar','CalendarController');
				   			//最强NBA视頻
				   			Route::resource('/nba_video','VideoController');
				   			//最强NBA视頻标签
				   			Route::resource('/nba_video_tag','VideoTagController');
				   			//最强NBA视频集合
				   			Route::resource('/nba_video_list','VideoListController');
				   			//最强NBA视频集合内容管理
				   			Route::resource('/nba_video_list_content','VideoListContentController');
				   			//最前NBA漫画标签管理
				   			Route::resource('/nba_manga_tag','MangaTagController');
				   			//最强NBA漫画管理
				   			Route::resource('/nba_manga','MangaController');
				   			//最前NBA图文管理
				   			Route::resource('/nba_content','ContentController');
				   			//最强NBA图文标签管理
				   			Route::resource('/nba_content_tag','ContentTagController');
				   		});
						   	//最强NBA首页栏目获取相对应的标签id或集合id
						   	Route::post('nba_column_data','ColumnDataController@getData');

					});

					//螺旋英雄谭路由管理
				   	Route::group(['prefix' => '/helix_saga','namespace' => 'HelixSaga'],function(){
				   		//需要权限访问的路由
				   		Route::group(['middleware' => ['checkPermission']] ,function() {
				   			//螺旋英雄谭批量导入图片
				   			Route::get('/picture','ImportPictureController@index');
				   			Route::post('/import_picture','ImportPictureController@upload');
				   			Route::post('/import_picture/upload_pic','ImportPictureController@uploadPic');
				   			//螺旋英雄谭图片目录管理
				   			Route::resource('/pic_catalog','PicCatalogController');
				   			//螺旋英雄谭数据表管理
				   			Route::resource('/data_dictionary','DataDictionaryController');
				   		});
				   	});

				   	// Route::group(['prefix' => '/data_acquisition','namespace' => 'DataAcquisition'],function(){
				   	// 	//需要权限访问的路由
				   	// 	Route::group(['middleware' => ['checkPermission']] ,function() {
				   	// 		//秒拍数据采集
				   	// 		Route::resource('/miaopai','MiaoPaiController');
				   	// 	});

				   	// });


			});

			
});




