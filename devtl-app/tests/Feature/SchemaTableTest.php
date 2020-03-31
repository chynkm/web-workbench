<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SchemaTableTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testGuestCannotViewSchemaTableList()
    {
        $schema = factory('App\Models\Schema')->create();

        $this->get(route('schemaTables.index', ['schema' => $schema->id]))
            ->assertRedirect('login');
    }

    public function testGuestCannotCreateSchemaTable()
    {
        $schema = factory('App\Models\Schema')->create();

        $attribute['name'] = $this->faker()->word;

        $this->get(route('schemaTables.store', ['schema' => $schema->id]), $attribute)
            ->assertRedirect('login');
    }

    public function testUserCanViewSchemaTablesListing()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $schemaTable = factory('App\Models\SchemaTable')->create([
            'user_id' => Auth::id(),
            'schema_id' => $schema->id,
        ]);

        $this->get(route('schemaTables.index', ['schema' => $schema->id]))
            ->assertSee($schemaTable->name);
    }

    public function testUserCanCreateSchemaTable()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $attribute['name'] = $this->faker()->word;

        $this->post(route('schemaTables.index', ['schema' => $schema->id]), $attribute)
            ->assertJsonStructure(['status']);

        $this->assertDatabaseHas('schema_tables', ['name' => $attribute['name']]);
    }


}