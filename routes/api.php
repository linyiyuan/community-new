<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api'], function () {

	// NBA社区接口
	Route::group(['prefix' => '/nba','namespace' => 'Nba'],function(){
		//首页接口
		Route::get('/home','HomeController@getHomeList');
		//漫画列表接口
		Route::get('/manga','MangaController@getMangaList');
		//漫画详情接口
		Route::get('/manga-detail/{id}','MangaController@getMangaDetail');
		//图文列表接口
		Route::get('/content','ContentController@getContentList');
		//图文列表详情接口
		Route::get('/content-detail/{id}','ContentController@getContentDetail');
		//视频列表标签接口
		Route::get('/video','VideoController@getVideoList');
		//老黄历接口
		Route::get('/calendar','CalendarController@getCalendarDetail');
	});

	// 螺旋英雄谭社区接口
	Route::group(['prefix' => '/helix_saga/','namespace' => 'HelixSaga'],function(){
	   //测试接口
	   Route::get('/text','TextController@text');
	   //螺旋英雄谭角色列表接口
	   Route::get('/role','RoleController@getList');
	   //螺旋英雄谭角色详情接口
	   Route::get('/role/detail/{id}','RoleController@getRoleDetail');
	   //螺旋英雄谭道具列表接口
	   Route::get('/tool','ToolController@getList');
	   //螺旋英雄谭道具详情接口
	   Route::get('/tool/detail/{id}','ToolController@getToolDetail');
	   //螺旋英雄谭技能列表接口
	   Route::get('/skill','SkillController@getList');
	   //螺旋英雄谭技能详情列表接口
	   Route::get('/skill/detail/{id}','SkillController@getSkillDetail');
	   //螺旋英雄谭角色道具混合搜索接口
	   Route::get('/search','BlendSearchController@getSearchRes');

	});



});
