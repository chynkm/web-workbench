<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function testRelationshipBelongsToATable()
    {
        $relationship = factory('App\Models\Relationship')->create();

        $this->assertInstanceOf('App\Models\SchemaTable', $relationship->primarySchemaTable);
    }
}
