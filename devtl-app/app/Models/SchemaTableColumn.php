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

    public function schemaTableColumnHistories()
    {
        return $this->hasMany(SchemaTableColumnHistory::class);
    }

    public function foreignRelationships()
    {
        return $this->hasMany(Relationship::class, 'foreign_table_column_id');
    }

    public function primaryRelationships()
    {
        return $this->hasMany(Relationship::class, 'primary_table_column_id');
    }

    public function createHistory()
    {
        $schemaTableColumnOriginal = $this->getOriginal();
        $this->schemaTableColumnHistories()
            ->create([
                'user_id' => $schemaTableColumnOriginal['user_id'],
                'schema_table_id' => $schemaTableColumnOriginal['schema_table_id'],
                'name' => $schemaTableColumnOriginal['name'],
                'datatype' => $schemaTableColumnOriginal['datatype'],
                'length' => $schemaTableColumnOriginal['length'],
                'primary_key' => $schemaTableColumnOriginal['primary_key'],
                'unique' => $schemaTableColumnOriginal['unique'],
                'zero_fill' => $schemaTableColumnOriginal['zero_fill'],
                'auto_increment' => $schemaTableColumnOriginal['auto_increment'],
                'unsigned' => $schemaTableColumnOriginal['unsigned'],
                'nullable' => $schemaTableColumnOriginal['nullable'],
                'default_value' => $schemaTableColumnOriginal['default_value'],
                'comment' => $schemaTableColumnOriginal['comment'],
                'order' => $schemaTableColumnOriginal['order'],
            ]);
    }
}
