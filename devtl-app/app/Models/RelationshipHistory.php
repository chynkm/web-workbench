<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelationshipHistory extends Model
{
    public $guarded = [], $timestamps = false;

    public function relationship()
    {
        return $this->belongsTo(Relationship::class);
    }
}
