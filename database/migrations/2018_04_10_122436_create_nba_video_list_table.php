<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNbaVideoListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nba_video_list', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sort')->default('0')->comment('顺序');
            $table->string('list_name',255)->default('')->comment('集合名');
            $table->tinyinteger('is_show')->default('1')->comment('是否显示 1:是 0:否');
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
        Schema::dropIfExists('nba_video_list');
    }
}
