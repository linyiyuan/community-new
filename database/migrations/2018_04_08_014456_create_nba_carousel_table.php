<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNbaCarouselTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nba_carousel', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',255)->default('')->comment('标题');
            $table->string('url',255)->default('')->comment('地址');
            $table->string('type',255)->default('')->comment('类型 0:链接 1:腾讯视屏 2:mp4视屏3:漫画4:图文');
            $table->string('img',255)->default('')->comment('封面图');
            $table->tinyInteger('is_show')->default('1')->comment('显示类型 1：显示 0：隐藏');
            $table->string('third_id',255)->default('0')->comment('第三方ID 漫画id 视屏id 图文id');
            $table->smallInteger('sort')->default('0')->comment('顺序');
            $table->smallInteger('pic_type')->default('1')->comment('封面图片类型 1:大图 0:小图');
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
        Schema::dropIfExists('nba_carousel');
    }
}

