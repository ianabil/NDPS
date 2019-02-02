<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagistratesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magistrates', function (Blueprint $table) {
            $table->increments('magistrate_id')->nullable(false);
            $table->string('magistrate_name')->nullable(false);
            $table->string('magistrate_deisgnation')->nullable(true);
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
        Schema::dropIfExists('magistrates');
    }
}
