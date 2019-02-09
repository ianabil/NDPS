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
            $table->string('drug_name')->nullable(false);
            $table->double('quantity_of_drug',5,2)->nullable(false);  
            $table->string('unit_name')->nullable(false);
            $table->date('date_of_seizure')->nullable(true);
            $table->date('date_of_disposal')->nullable(true);
            $table->double('disposal_quantity',5,2)->nullable(true);  
            $table->string('unit_of_disposal_quantity')->nullable(true);
            $table->double('undisposed_quantity',5,2)->nullable(true);  
            $table->string('undisposed_unit')->nullable(true);
            $table->text('case_details')->nullable(true);
            $table->integer('district_id')->nullable(false);
            $table->integer('agency_id')->nullable(true);
            $table->string('storage_location')->nullable(true);
            $table->integer('court_id')->nullable(true);
            $table->integer('certification_court_id')->nullable(true);
            $table->date('date_of_certification')->nullable(true);
            $table->text('remarks')->nullable(true);
            $table->string('user_name');
            $table->string('submit_flag');
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
