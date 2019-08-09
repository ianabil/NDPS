<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryDeletedData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_deleted_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('table_name')->nullable(false);
            $table->json('deleted_data')->nullable(false);
            $table->timestamp('deleted_time')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_deleted_data');
    }
}
