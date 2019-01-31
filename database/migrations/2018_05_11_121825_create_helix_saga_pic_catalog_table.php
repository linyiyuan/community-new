<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHelixSagaPicCatalogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helix_saga_pic_catalog', function (Blueprint $table) {
            $table->increments('id');
            $table->string('desc',255)->default('')->comment('目录描述');
            $table->string('path',255)->default('')->comment('目录路径');
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
        Schema::dropIfExists('helix_saga_pic_catalog');
    }
}
