<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemaTableColumn extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function schemaTable()
    {
        return $this->belongsTo(SchemaTable::class);
    }
}
