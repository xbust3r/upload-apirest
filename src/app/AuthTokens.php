<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthTokens extends Model
{
    //
    protected $table = 'auth_tokens';

    protected $fillable = ['name', 'token_user', 'token_pass'];
}
