<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('verifications')) {
            Schema::create('verifications', function (Blueprint $table) {
                $table->id();
                $table->string('phone_number')->nullable();
                $table->string('country_code')->nullable();
                $table->string('email')->nullable();
                $table->string('code')->nullable();
                $table->enum('status', ['pending', 'failed', 'verified'])->default('pending');
                $table->dateTime('expired_at');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verifications');
    }
}
