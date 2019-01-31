<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNbaMangaImgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nba_manga_imgs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('manga_id')->default('0')->comment('漫画管理id');
            $table->string('img',255)->default('')->comment('漫画图片');
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
        Schema::dropIfExists('nba_manga_imgs');
    }
}
