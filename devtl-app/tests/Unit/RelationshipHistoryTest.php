<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationshipHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function testRelationshipHistoryBelongsToARelationship()
    {
        $relationshipHistory = factory('App\Models\RelationshipHistory')->create();

        $this->assertInstanceOf('App\Models\Relationship', $relationshipHistory->relationship);
    }
}
