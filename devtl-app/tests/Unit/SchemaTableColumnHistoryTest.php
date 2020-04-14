<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchemaTableColumnHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function testSchemaTableColumnHistoryBelongsToASchemaTableColumn()
    {
        $schemaTableColumnHistory = factory('App\Models\SchemaTableColumnHistory')->create();

        $this->assertInstanceOf('App\Models\SchemaTableColumn', $schemaTableColumnHistory->schemaTableColumn);
    }
}
