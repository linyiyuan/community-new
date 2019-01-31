<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNbaVideoTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nba_video_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sort')->default('1')->comment("顺序")->nullable();
            $table->string('name',255)->default('')->comment('标签名，base64编码');
            $table->tinyInteger('is_show')->default('0')->comment('是否显示 1:是 0:否');
            $table->string('desc',255)->default('')->comment('说明')->nullable();
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
        Schema::dropIfExists('nba_video_tag');
    }
}
