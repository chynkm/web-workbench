<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function testRelationshipBelongsToATable()
    {
        $relationship = factory('App\Models\Relationship')->create();

        $this->assertInstanceOf('App\Models\SchemaTable', $relationship->foreignSchemaTable);
    }

    public function testRelationshipBelongsToAColumnForeign()
    {
        $relationship = factory('App\Models\Relationship')->create();

        $this->assertInstanceOf('App\Models\SchemaTableColumn', $relationship->foreignSchemaTableColumn);
    }

    public function testRelationshipBelongsToAColumnPrimary()
    {
        $relationship = factory('App\Models\Relationship')->create();

        $this->assertInstanceOf('App\Models\SchemaTableColumn', $relationship->primarySchemaTableColumn);
    }

    public function testRelationshipHasManyRelationshipHistories()
    {
        $relationship = factory('App\Models\Relationship')->create();

        $this->assertInstanceOf(Collection::class, $relationship->relationshipHistories);
    }
}
