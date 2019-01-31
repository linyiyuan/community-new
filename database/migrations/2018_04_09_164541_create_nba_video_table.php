<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNbaVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nba_video', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyinteger('tag_id')->default('0')->comment('标签Id');
            $table->tinyinteger('type')->default('0')->comment('视频类型 1:MP4视频 2:腾讯视频');
            $table->string('data',255)->defalut('')->comment('MP4地址/腾讯视频id');
            $table->string('cover',255)->default('')->comment('视频封面图');
            $table->string('title',255)->default('')->comment('标题');
            $table->tinyinteger('sort')->default('0')->comment('排序');
            $table->tinyinteger('is_show')->default('1')->comment('是否显示 1:是 0:否');
            $table->integer('time')->default('0')->comment('发布时间');
            $table->integer('admin_id')->default('0')->comment('管理员id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nba_video');
    }
}
