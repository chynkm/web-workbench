<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchemaTableHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function testSchemaTableHistoryBelongsToASchemaTable()
    {
        $schemaTableHistory = factory('App\Models\SchemaTableHistory')->create();

        $this->assertInstanceOf('App\Models\SchemaTable', $schemaTableHistory->schemaTable);
    }
}
