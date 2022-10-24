<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadsRequests extends Model
{
    //
    protected $table = 'uploads_requests';

    protected $fillable = ['ip', 'auth_token_id'];
}
