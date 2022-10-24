<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AuthTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('token_user')->unique();
            $table->string('token_pass')->unique();
            $table->timestamps();
        });
        DB::table('auth_tokens')->insert(
            array(
                'name' => 'default',
                'token_user' => 'admin',
                'token_pass' => 'key'
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_tokens');
    }
}
