<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TableColumnTest extends TestCase
{
    use RefreshDatabase;

    public function testSchemaTableColumnBelongsToATable()
    {
        $schemaTableColumn = factory('App\Models\SchemaTableColumn')->create();

        $this->assertInstanceOf('App\Models\SchemaTable', $schemaTableColumn->schemaTable);
    }
}
