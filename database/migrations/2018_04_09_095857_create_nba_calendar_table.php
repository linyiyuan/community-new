<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNbaCalendarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nba_calendar', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->default('0')->comment('admin_id');
            $table->string('good_content',255)->default('')->comment('宜');
            $table->string('bad_content',255)->default('')->comment('忌');
            $table->string('person_name',50)->default('')->comment('名人');
            $table->string('person_word',50)->default('')->comment('名言');
            $table->string('avatar',255)->default('')->comment('名人头像');
            $table->tinyInteger('is_show')->default('1')->comment('是否显示1:显示 0:隐藏');
            $table->string('tip',1025)->default('')->nullable()->comment('活动提醒');
            $table->integer('time')->default('0')->comment('开始时间');
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
        Schema::dropIfExists('nba_calendar');
    }
}
