<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('device_tokens')) {
            Schema::create('device_tokens', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger("user_id");
                $table->string('device_token');
                $table->string('device_type');
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
        Schema::dropIfExists('device_tokens');
    }
}
