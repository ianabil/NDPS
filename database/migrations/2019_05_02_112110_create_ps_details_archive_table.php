<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePsDetailsArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ps_details_archive', function (Blueprint $table) {
            $table->increments('ps_id');
            $table->string('ps_name')->nullable(false);
            $table->integer('district_id')->nullable(false);
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
        Schema::dropIfExists('ps_details_archieve');
    }
}
