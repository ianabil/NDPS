<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourtDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('court_details', function (Blueprint $table) {
            $table->increments('court_id')->nullable(false);
            $table->string('court_name')->nullable(false);
            $table->integer('district_id')->nullable(false);
            $table->foreign('district_id')->references('district_id')->on('districts');

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
        Schema::dropIfExists('court_details');
    }
}
