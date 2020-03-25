<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserToken extends Model
{
    public $guarded = [], $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getToken()
    {
        do {
            $token = Str::random(20);
        } while (
            $this->where('token', $token)
                ->whereNull('logged_in')
                ->first() instanceof this
        );

        return $token;
    }

}
