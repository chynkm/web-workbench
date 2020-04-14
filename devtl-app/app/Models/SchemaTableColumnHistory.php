<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchemaTableColumnHistory extends Model
{
    public $guarded = [], $timestamps = false;

    public function schemaTableColumn()
    {
        return $this->belongsTo(SchemaTableColumn::class);
    }
}
