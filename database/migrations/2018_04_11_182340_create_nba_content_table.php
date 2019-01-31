<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNbaContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nba_content', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->default('0')->comment('管理员id');
            $table->string('title',255)->default('')->comment('标题');
            $table->integer('tag_id')->default('0')->comment('图文标签');
            $table->integer('time')->default('0')->comment('发布日期');
            $table->string('cover',255)->default('')->comment('封面图');
            $table->integer('sort')->default('0')->comment('优先级');
            $table->tinyinteger('is_show')->default('1')->comment('是否显示 1:是 0:否');
            $table->text('content')->comment('内容 ');
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
        Schema::dropIfExists('nba_content');
    }
}
