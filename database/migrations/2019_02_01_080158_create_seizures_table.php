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
            $table->string('drug_id')->nullable(false);
            $table->double('quantity_of_drug',5,2)->nullable(false);  
            $table->string('seizure_quantity_weighing_unit_id')->nullable(false);
            $table->date('date_of_seizure')->nullable(true);
            $table->date('date_of_disposal')->nullable(true);
            $table->double('disposal_quantity',5,2)->nullable(true);  
            $table->string('disposal_quantity_weighing_unit')->nullable(true);
            $table->double('undisposed_quantity',5,2)->nullable(true);  
            $table->string('undisposed_quantity_weighing_unit')->nullable(true); 
            $table->string('storage_location')->nullable(true);            
            $table->integer('stakeholder_id')->nullable(true);            
            $table->integer('district_id')->nullable(false);  
            $table->integer('certification_court_id')->nullable(true); 
            $table->date('date_of_certification')->nullable(true);
            $table->string('certification_flag');
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
