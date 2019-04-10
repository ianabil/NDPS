<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePsDistRelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {    
        Schema::create('ps_dist_rels', function (Blueprint $table) {
            $table->integer('ps_id')->nullable(false);
            $table->integer('district_id')->nullable(false);
            $table->integer('court_id')->nullable(false);

            $table->primary(['ps_id','district_id']);
            $table->foreign('district_id')->references('district_id')->on('districts');
            $table->foreign('ps_id')->references('ps_id')->on('ps_details');

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
        Schema::dropIfExists('ps_dist_rels');
    }
}
