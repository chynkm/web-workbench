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

    public function schemaTableHistories()
    {
        return $this->hasMany(SchemaTableHistory::class);
    }

    public function createHistory()
    {
        $schemaTableOriginal = $this->getOriginal();
        $this->schemaTableHistories()
            ->create([
                'user_id' => $schemaTableOriginal['user_id'],
                'schema_id' => $schemaTableOriginal['schema_id'],
                'name' => $schemaTableOriginal['name'],
                'engine' => $schemaTableOriginal['engine'],
                'collation' => $schemaTableOriginal['collation'],
                'description' => $schemaTableOriginal['description'],
                'x_index' => $schemaTableOriginal['x_index'],
                'y_index' => $schemaTableOriginal['y_index'],
                'height' => $schemaTableOriginal['height'],
                'width' => $schemaTableOriginal['width'],
            ]);
    }
}
