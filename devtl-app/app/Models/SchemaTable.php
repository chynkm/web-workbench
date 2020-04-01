<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemaTable extends Model
{
    use SoftDeletes;

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
