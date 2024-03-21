<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_documents')) {
            Schema::create('user_documents', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->tinyInteger('document_type')->comment('1 - Deed, 2 - Emirates, 3 - Passport, 4 - Other');
                $table->string('document');
                $table->string('description')->nullable();
                $table->tinyInteger('status')->default(0)->comment('0 - Pending, 1 - Approved');
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
        Schema::dropIfExists('user_documents');
    }
}
