<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNbaMangaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nba_manga', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',255)->default('')->comment('漫画标题 base64编码');
            $table->integer('admin_id')->defualt('0')->comment('管理员id');
            $table->integer('sort')->defualt('0')->comment('漫画顺序');
            $table->integer('time')->defualt('0')->comment('发布日期');
            $table->integer('tag_id')->default('0')->comment('标签id 0:代表无标签');
            $table->tinyinteger('is_show')->default('1')->comment('是否显示 1:是 0:否');
            $table->tinyinteger('type')->default('1')->comment('类型 1:单列 2:双列');
            $table->string('cover',255)->default('')->comment('漫画封面图');
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
        Schema::dropIfExists('nba_manga');
    }
}
