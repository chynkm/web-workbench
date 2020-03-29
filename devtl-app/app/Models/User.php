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

    public function schemas()
    {
        return $this->belongsToMany(Schema::class);
    }

    public function getLinkLoginToken(UserToken $userToken)
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

    public function createRegistrationSchema(Schema $schema)
    {
        $schema = $schema->create([
            'name' => config('env.first_schema_name'),
        ]);

        $this->schemas()
            ->sync([$schema->id => ['owner' => true]]);

        $schemaTable = $schema->schemaTables()
            ->create([
                'user_id' => $this->id,
                'name' => config('env.first_table_name'),
            ]);

        $schemaTable->schemaTableColumns()
            ->create([
                'user_id' => $this->id,
                'name' => config('env.first_column_name'),
                'type' => config('env.first_column_type'),
                'primary_key' => config('env.first_column_primary_key'),
                'auto_increment' => config('env.first_column_auto_increment'),
                'unsigned' => config('env.first_column_unsigned'),
                'nullable' => config('env.first_column_nullable'),
                'order' => config('env.first_column_order'),
            ]);

        return $this;
    }
}
