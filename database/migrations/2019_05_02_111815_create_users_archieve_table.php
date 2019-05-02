<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersArchieveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_archieve', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id')->unique();
            $table->string('user_name');
            $table->string('password');
            $table->integer('stakeholder_id')->unique()->nullable(true);
            $table->integer('court_id')->unique()->nullable(true);
            $table->string('email')->unique()->nullable(true);
            $table->string('contact_no')->nullable(true);
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
        Schema::dropIfExists('users_archieve');
    }
}
