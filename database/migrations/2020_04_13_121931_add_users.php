<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('users')->insert(array(
            'username' => 'admin',
            'firstname' => 'IT',
            'lastname' => 'Admministrator',
            'login_password' => '$2y$10$9Gb6UgTSI1mAU8aj0PYn0.N/uMPUIKQOMf6aXxWuDO10OaxX1FC8y',
            'login_password_salt' => 'AfM1',
            'create_ip' => 0,
            'create_time' => time(),
            'last_login_ip' => 0,
            'last_login_time' => 0,
            'last_active_time' => 0,
            'login_count' => 0,
            'role_id' => 9,
            'status' => 1
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('users')->where('username', '=', 'admin')->delete();
    }
}
