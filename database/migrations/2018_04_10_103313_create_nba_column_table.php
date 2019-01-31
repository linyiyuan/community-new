<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNbaColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nba_column', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sort')->default('0')->comment('顺序');
            $table->string('column_name',255)->default('')->comment('栏目名');
            $table->tinyinteger('type')->default('0')->comment('栏目类型 1:视频集合 2:视频标签 3:漫画标签');
            $table->integer('data_id')->defautl('0')->comment('标签或者集合的关联id');
            $table->tinyinteger('is_show')->default('0')->comment('是否显示 1:是 0:否');
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
        Schema::dropIfExists('nba_column');
    }
}
