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
        $attributes['name'] = Str::random(10);
        $attributes['engine'] = Str::random(15);
        $attributes['collation'] = Str::random(25);

        $this->post(route('schemaTables.store', ['schema' => $schema->id]), $attributes)
            ->assertJsonStructure(['status']);

        $this->assertDatabaseHas('schema_tables', [
            'name' => $attributes['name'],
            'engine' => $attributes['engine'],
            'collation' => $attributes['collation'],
        ]);
    }

    /**
     * @dataProvider invalidSchemaTableNameProvider
     */
    public function testInvalidSchemaTableNames($input, $field)
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);

        $this->post(route('schemaTables.store', ['schema' => $schema->id]), ['name' => $input])
            ->assertSessionHasErrors($field);
    }

    public function invalidSchemaTableNameProvider()
    {
        return [
            ['', 'name'],
            [null, 'name'],
            [Str::random(101), 'name'],
            ['spaced table', 'name'],
            ['', 'engine'],
            [null, 'engine'],
            [Str::random(21), 'engine'],
            ['', 'collation'],
            [null, 'collation'],
            [Str::random(41), 'collation'],
        ];
    }

    public function testAUserCannotViewOtherUsersSchemaTable()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();

        $this->get(route('schemaTables.index', ['schema' => $schema->id]))
            ->assertRedirect(route('schemas.index'));
    }


    /** Schema Table Column */

    public function testGuestCannotFetchSchemaTableColumns()
    {
        $schema = factory('App\Models\Schema')->create();
        $schemaTable = factory('App\Models\SchemaTable')->create(['schema_id' => $schema->id]);

        $this->get(route('schemaTables.columns', ['schemaTable' => $schemaTable->id]))
            ->assertRedirect('login');
    }

    public function testGuestCannotCreateSchemaTableColumns()
    {
        $schema = factory('App\Models\Schema')->create();
        $schemaTable = factory('App\Models\SchemaTable')->create(['schema_id' => $schema->id]);
        $schemaTableColumn = factory('App\Models\SchemaTableColumn')->raw(['schema_id' => $schema->id]);

        $this->post(route('schemaTables.updateColumns', ['schemaTable' => $schemaTable->id]), $schemaTableColumn)
            ->assertRedirect('login');
    }

    public function testUserCanViewSchemaTableColumnsListing()
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
        $schemaTableColumns = factory('App\Models\SchemaTableColumn', 3)->create([
            'user_id' => Auth::id(),
            'schema_table_id' => $schemaTable->id,
        ]);

        $this->get(route('schemaTables.columns', ['schemaTable' => $schemaTable->id]))
            ->assertOk()
            ->assertJsonStructure(['status', 'html']);
    }

    public function testUserCanCreateSchemaTableColumns()
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
        $schemaTableColumn = factory('App\Models\SchemaTableColumn')->raw([
            'user_id' => Auth::id(),
            'schema_table_id' => $schemaTable->id,
        ]);

        $this->post(route('schemaTables.updateColumns', ['schemaTable' => $schemaTable->id]), $schemaTableColumn)
            ->assertJsonStructure(['status']);

        $this->assertDatabaseHas('schema_table_columns', [
            'name' => $attributes['name'],
            'engine' => $attributes['engine'],
            'collation' => $attributes['collation'],
        ]);
    }

}
