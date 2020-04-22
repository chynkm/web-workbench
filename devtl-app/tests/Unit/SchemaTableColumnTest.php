<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
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

    public function testSchemaTableColumnHasManySchemaTableColumnHistories()
    {
        $schemaTableColumn = factory('App\Models\SchemaTableColumn')->create();

        $this->assertInstanceOf(Collection::class, $schemaTableColumn->schemaTableColumnHistories);
    }

    public function testSchemaTableColumnHasManyForeignRelationships()
    {
        $schemaTableColumn = factory('App\Models\SchemaTableColumn')->create();

        $this->assertInstanceOf(Collection::class, $schemaTableColumn->foreignRelationships);
    }

    public function testSchemaTableColumnHasManyPrimaryRelationships()
    {
        $schemaTableColumn = factory('App\Models\SchemaTableColumn')->create();

        $this->assertInstanceOf(Collection::class, $schemaTableColumn->primaryRelationships);
    }
}
