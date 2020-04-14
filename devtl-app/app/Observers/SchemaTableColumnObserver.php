<?php

namespace App\Observers;

use App\Models\SchemaTableColumn;

class SchemaTableColumnObserver
{
    public function updated(SchemaTableColumn $schemaTableColumn)
    {
        $schemaTableColumn->createHistory();
    }
}
