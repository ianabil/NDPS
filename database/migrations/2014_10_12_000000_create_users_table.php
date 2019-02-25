<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id')->unique();
            $table->string('user_name');
            $table->string('password');
            $table->integer('stakeholder_id')->unique()->nullable(true);
            $table->string('email')->unique()->nullable(true);
            $table->string('contact_no')->nullable(true);
            $table->timestamp('email_verified_at')->nullable(true);            
            $table->string('user_type');
            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('login_at')->nullable(true);
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
