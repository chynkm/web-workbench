<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchemaTableHistory extends Model
{
    public $guarded = [], $timestamps = false;

    public function schemaTable()
    {
        return $this->belongsTo(SchemaTable::class);
    }
}
