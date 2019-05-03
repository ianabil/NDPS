<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNarcoticUnitsArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('narcotic_units_archive', function (Blueprint $table) {
            $table->integer('narcotic_id');
            $table->integer('unit_id');            
            $table->timestamps();

            $table->primary(['narcotic_id','unit_id']);
            $table->foreign('narcotic_id')->references('drug_id')->on('narcotics');
            $table->foreign('unit_id')->references('unit_id')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('narcotic_units_archieve');
    }
}
