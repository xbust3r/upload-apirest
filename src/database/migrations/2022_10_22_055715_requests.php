<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Requests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads_requests', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('ip');
            $table->unsignedBigInteger('auth_token_id');
            $table->foreign('auth_token_id')->references('id')->on('auth_tokens');
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
        //
        Schema::dropIfExists('uploads_requests');
    }
}
