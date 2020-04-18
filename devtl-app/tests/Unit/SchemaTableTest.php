<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchemaTableTest extends TestCase
{
    use RefreshDatabase;

    public function testSchemaTableBelongsToASchema()
    {
        $schemaTable = factory('App\Models\SchemaTable')->create();

        $this->assertInstanceOf('App\Models\Schema', $schemaTable->schema);
    }

    public function testSchemaTableHasManySchemaTableColumns()
    {
        $schemaTable = factory('App\Models\SchemaTable')->create();

        $this->assertInstanceOf(Collection::class, $schemaTable->schemaTableColumns);
    }

    public function testSchemaTableHasManySchemaTableHistories()
    {
        $schemaTable = factory('App\Models\SchemaTable')->create();

        $this->assertInstanceOf(Collection::class, $schemaTable->schemaTableHistories);
    }

    public function testSchemaTableHasManyRelationships()
    {
        $schemaTable = factory('App\Models\SchemaTable')->create();

        $this->assertInstanceOf(Collection::class, $schemaTable->foreignRelationships);
    }
}
