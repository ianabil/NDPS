<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNarcoticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('narcotics', function (Blueprint $table) {
            $table->integer('drug_id');
            $table->string('drug_name')->nullable(false);
            $table->integer('drug_unit')->nullable(false);
            $table->timestamps();
            $table->primary(['drug_id','drug_unit']);
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('narcotics');
    }
}
