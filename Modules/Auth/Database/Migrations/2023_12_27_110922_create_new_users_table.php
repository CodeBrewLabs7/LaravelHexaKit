<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the users table exists
        if (Schema::hasTable('users')) {
            // Drop the users table if it exists
            Schema::dropIfExists('users');
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('email', 60)->unique();
            $table->string('phone_number', 24)->nullable();
            $table->tinyInteger('is_email_verified')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->tinyInteger('is_phone_verified')->default(0);
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('type')->default(0);
            $table->tinyInteger('status')->default(0)->comment('0 - pending, 1 - active, 2 - blocked, 3 - inactive');
            $table->bigInteger('country_id')->unsigned()->nullable();
            $table->bigInteger('role_id')->unsigned()->nullable();
            $table->string('auth_token')->nullable();
            $table->index('phone_number');
            $table->string('system_id')->nullable();
            $table->index('type');
            $table->index('status');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
