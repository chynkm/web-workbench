<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Relationship extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function foreignSchemaTable()
    {
        return $this->belongsTo(SchemaTable::class, 'foreign_table_id');
    }

    public function relationshipHistories()
    {
        return $this->hasMany(RelationshipHistory::class);
    }

    public function createHistory()
    {
        $relationshipOriginal = $this->getOriginal();
        $this->relationshipHistories()
            ->create([
                'user_id' => $relationshipOriginal['user_id'],
                'foreign_table_id' => $relationshipOriginal['foreign_table_id'],
                'foreign_table_column_id' => $relationshipOriginal['foreign_table_column_id'],
                'primary_table_id' => $relationshipOriginal['primary_table_id'],
                'primary_table_column_id' => $relationshipOriginal['primary_table_column_id'],
            ]);
    }
}
