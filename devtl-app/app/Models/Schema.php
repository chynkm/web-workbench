<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schema extends Model
{
    public $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function schemaTables()
    {
        return $this->hasMany(SchemaTable::class);
    }
}
