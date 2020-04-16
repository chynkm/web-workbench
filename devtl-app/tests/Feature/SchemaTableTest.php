<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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

        $this->post(route('schemaTables.store', ['schema' => $schema->id]), $attribute)
            ->assertRedirect('login');
    }

    public function testGuestCannotEditSchemaTable()
    {
        $schema = factory('App\Models\Schema')->create();
        $schemaTable = factory('App\Models\SchemaTable')->create();
        $attribute['name'] = $this->faker()->word;

        $this->post(route('schemaTables.update', ['schemaTable' => $schemaTable->id]), $attribute)
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
        $attributes['name'] = Str::random(10);
        $attributes['engine'] = Str::random(15);
        $attributes['collation'] = Str::random(25);

        $this->post(route('schemaTables.store', ['schema' => $schema->id]), $attributes)
            ->assertJsonStructure(['status', 'table_url', 'column_url']);

        $this->assertDatabaseHas('schema_tables', [
            'name' => $attributes['name'],
            'engine' => $attributes['engine'],
            'collation' => $attributes['collation'],
        ]);
    }

    public function testUserCanEditSchemaTable()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $schemaTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);

        $attributes['name'] = Str::random(10);
        $attributes['engine'] = Str::random(15);
        $attributes['collation'] = Str::random(25);

        $this->post(route('schemaTables.update', ['schemaTable' => $schemaTable->id]), $attributes)
            ->assertJsonStructure(['status']);

        $this->assertDatabaseHas('schema_tables', [
            'name' => $attributes['name'],
            'engine' => $attributes['engine'],
            'collation' => $attributes['collation'],
        ]);

        $this->assertDatabaseHas('schema_table_histories', [
            'name' => $schemaTable->name,
            'engine' => $schemaTable->engine,
            'collation' => $schemaTable->collation,
        ]);
    }

    /**
     * @dataProvider invalidSaveSchemaTableProvider
     */
    public function testInvalidSaveSchemaTables($input, $field)
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $schemaTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'name' => 'users',
        ]);

        $this->post(route('schemaTables.store', ['schema' => $schema->id]), ['name' => $input])
            ->assertSessionHasErrors($field);
    }

    public function invalidSaveSchemaTableProvider()
    {
        return [
            ['', 'name'],
            [null, 'name'],
            [Str::random(101), 'name'],
            ['spaced table', 'name'],
            ['users', 'name'],
            ['', 'engine'],
            [null, 'engine'],
            [Str::random(21), 'engine'],
            ['', 'collation'],
            [null, 'collation'],
            [Str::random(41), 'collation'],
        ];
    }

    public function testInvalidUpdateSchemaTables()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $schemaTables = factory('App\Models\SchemaTable', 2)->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);

        $attributes['name'] = $schemaTables->first()->name;
        $attributes['engine'] = $schemaTables->first()->engine;
        $attributes['collation'] = $schemaTables->first()->engine;


        $this->post(route('schemaTables.update', ['schemaTable' => $schemaTables->last()->id]), $attributes)
            ->assertSessionHasErrors('name');
    }

    public function testAUserCannotViewOtherUsersSchemaTable()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();

        $this->get(route('schemaTables.index', ['schema' => $schema->id]))
            ->assertRedirect(route('schemas.index'));
    }

    public function testGuestCannotDeleteSchemaTableList()
    {
        $schemaTable = factory('App\Models\SchemaTable')->create();

        $this->get(route('schemaTables.delete', ['schemaTable' => $schemaTable->id]))
            ->assertRedirect('login');
    }

    public function testUserCannotDeleteOthersSchemaTable()
    {
        $this->signIn();
        $schemaTable = factory('App\Models\SchemaTable')->create();

        $this->get(route('schemaTables.delete', ['schemaTable' => $schemaTable->id]))
            ->assertRedirect(route('schemas.index'));
    }

    public function testUserDeleteSchemaTable()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $schemaTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);

        $this->get(route('schemaTables.delete', ['schemaTable' => $schemaTable->id]))
            ->assertOk()
            ->assertJsonStructure(['status']);

        $this->assertSoftDeleted('schema_tables', [
            'id' => $schemaTable->id,
            'user_id' => Auth::id(),
        ]);
    }
}
