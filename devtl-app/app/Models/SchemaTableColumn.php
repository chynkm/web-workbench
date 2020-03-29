<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchemaTableColumn extends Model
{
    protected $guarded = [];

    public function schemaTable()
    {
        return $this->belongsTo(SchemaTable::class);
    }
}
