<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_details', function (Blueprint $table) {
            $table->increments('storage_id');
            $table->string('storage_name')->nullable(false);
            $table->integer('district_id')->nullable(false);
            $table->string('display')->nullable(false);
            $table->timestamps();

            $table->foreign('district_id')->references('district_id')->on('districts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storage_details');
    }
}
