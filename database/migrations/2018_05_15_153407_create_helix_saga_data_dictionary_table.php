<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHelixSagaDataDictionaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helix_saga_data_dictionary', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->comment('数据表名');
            $table->string('url')->default('')->comment('数据库表路径名');
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
        Schema::dropIfExists('helix_saga_data_dictionary');
    }
}
