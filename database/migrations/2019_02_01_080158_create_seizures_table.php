<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeizuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seizures', function (Blueprint $table) {
            $table->increments('seizure_id')->nullable(false);
            $table->integer('drug_id')->nullable(false);
            $table->double('quantity_of_drug',5,2)->nullable(false);  
            $table->string('unit_name')->nullable(false);
            $table->timestamp('date_of_seizure')->nullable(false);
            $table->string('status_of_drug')->nullable(false);
            $table->text('case_details')->nullable(true);
            $table->integer('district_id')->nullable(false);
            $table->integer('agency_id')->nullable(true);
            $table->integer('storage_id')->nullable(true);
            $table->integer('court_id')->nullable(true);
            $table->timestamp('date_of_certification');
            $table->text('remarks')->nullable(true);
            $table->string('user_name');
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
        Schema::dropIfExists('seizures');
    }
}
