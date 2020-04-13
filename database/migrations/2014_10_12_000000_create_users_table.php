<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->string("username", 50);
            $table->string("firstname",50);
            $table->string("lastname",50);
            $table->string("login_password", 65);
            $table->string("login_password_salt", 4);
            $table->integer("create_ip");
            $table->integer("create_time");
            $table->integer("last_login_ip");
            $table->integer("last_login_time");
            $table->integer("last_active_time");
            $table->integer("login_count");
            $table->tinyInteger("role_id");
            $table->tinyInteger("status")->default(1);
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
