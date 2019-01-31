<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNbaListVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nba_list_video', function (Blueprint $table) {
            $table->integer('video_list_id')->unsigned();
            $table->integer('video_id')->unsigned();

            $table->foreign('video_list_id')->references('id')->on('nba_video_list')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('video_id')->references('id')->on('nba_video')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['video_list_id', 'video_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nba_list_video');
    }
}
