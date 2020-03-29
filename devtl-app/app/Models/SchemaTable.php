<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchemaTable extends Model
{
    protected $guarded = [];

    public function schema()
    {
        return $this->belongsTo(Schema::class);
    }

    public function schemaTableColumns()
    {
        return $this->hasMany(SchemaTableColumn::class);
    }
}
