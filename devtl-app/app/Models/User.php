<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userTokens()
    {
        return $this->hasMany(UserToken::class);
    }

    public function getMagicLoginToken(UserToken $userToken)
    {
        $token = $userToken->getToken();
        $this->userTokens()
            ->create(['token' => $token]);

        return encrypt($this->id.'|'.$token);
    }

    public function logInAfterlogOutOtherSessions()
    {
        $lastSessionId = Session::getHandler()
            ->read($this->session_id);

        if ($lastSessionId) {
            Session::getHandler()
                ->destroy($this->session_id);
        }

        Auth::login($this, true);
        $this->remember_token = null;
        $this->session_id = Session::getId();
        $this->save();
    }
}
