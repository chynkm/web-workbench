<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Relationship extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function primarySchemaTable()
    {
        return $this->belongsTo(SchemaTable::class, 'primary_table_id');
    }
}
