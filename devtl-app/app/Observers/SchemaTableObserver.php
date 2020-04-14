<?php

namespace App\Observers;

use App\Models\SchemaTable;

class SchemaTableObserver
{
    public function updated(SchemaTable $schemaTable)
    {
        $schemaTable->createHistory();
    }
}
