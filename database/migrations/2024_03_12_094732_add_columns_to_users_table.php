<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'country_flag')) {
                $table->string('country_flag')->after('password')->nullable();
            }
            if (!Schema::hasColumn('users', 'country_code')) {
                $table->string('country_code')->after('country_flag')->nullable();
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->tinyInteger('role')->after('image')->comment('1 for admin, 2 for user');
            }
            if (!Schema::hasColumn('users', 'device_type')) {
                $table->string('device_type')->after('role')->nullable();
            }
            if (!Schema::hasColumn('users', 'is_otp_sent')) {
                $table->tinyInteger('is_otp_sent')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
