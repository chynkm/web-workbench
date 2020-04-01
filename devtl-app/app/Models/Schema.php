<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schema extends Model
{
    use SoftDeletes;

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
