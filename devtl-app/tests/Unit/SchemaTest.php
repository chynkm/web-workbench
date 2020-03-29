<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchemaTest extends TestCase
{
    use RefreshDatabase;

    public function testSchemaHasManyUsers()
    {
        $schema = factory('App\Models\Schema')->create();

        $this->assertInstanceOf(Collection::class, $schema->users);
    }

    public function testSchemaHasManyTables()
    {
        $schema = factory('App\Models\Schema')->create();

        $this->assertInstanceOf(Collection::class, $schema->schemaTables);
    }
}
