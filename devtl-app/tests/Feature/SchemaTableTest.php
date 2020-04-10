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
    }

    /**
     * @dataProvider invalidSchemaTableProvider
     */
    public function testInvalidSchemaTables($input, $field)
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);

        $this->post(route('schemaTables.store', ['schema' => $schema->id]), ['name' => $input])
            ->assertSessionHasErrors($field);
    }

    public function invalidSchemaTableProvider()
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
        $schemaTableColumn = factory('App\Models\SchemaTableColumn')->raw(['schema_table_id' => $schemaTable->id]);

        $this->post(route('schemaTables.updateColumns', ['schemaTable' => $schemaTable->id]), $schemaTableColumn)
            ->assertRedirect('login');
    }

    public function testGuestCannotUpdateSchemaTableColumns()
    {
        $schema = factory('App\Models\Schema')->create();
        $schemaTable = factory('App\Models\SchemaTable')->create(['schema_id' => $schema->id]);
        $schemaTableColumn = factory('App\Models\SchemaTableColumn')->create(['schema_table_id' => $schemaTable->id]);
        $attribute['name'] = Str::random(10);

        $this->post(route('schemaTables.updateColumns', ['schemaTable' => $schemaTable->id]), $attribute)
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

    public function testAUserCannotViewOtherUsersSchemaTableColumns()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $schemaTable = factory('App\Models\SchemaTable')->create([]);
        $schemaTableColumns = factory('App\Models\SchemaTableColumn', 3)->create([
            'schema_table_id' => $schemaTable->id,
        ]);

        $this->get(route('schemaTables.columns', ['schemaTable' => $schemaTable->id]))
            ->assertRedirect(route('schemas.index'));
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

        $data['schema_table_columns'] = [
            'id' => [
                null,
                null,
                null, // last row is removed
            ],
            'name' => [
                'id',
                'name',
            ],
            'datatype' => [
                'int',
                'varchar',
            ],
            'length' => [
                20,
                40,
            ],
            'unsigned' => [
                'true',
                'false',
            ],
            'auto_increment' => [
                'true',
                'false',
            ],
            'primary_key' => [
                'true',
                'false',
            ],
            'unique' => [
                'false',
                'false',
            ],
            'zero_fill' => [
                'false',
                'false',
            ],
            'nullable' => [
                'false',
                'false',
            ],
            'comment' => [
                null,
                null,
            ],
            'default_value' => [
                null,
                null,
            ],
            'order' => [
                null,
                null,
            ],
        ];

        $this->post(route('schemaTables.updateColumns', ['schemaTable' => $schemaTable->id]), $data)
            ->assertOk()
            ->assertJsonStructure(['status', 'html']);

        $this->assertDatabaseHas('schema_table_columns', [
            'name' => 'id',
            'datatype' => 'int',
            'length' => 20,
            'order' => 1
        ]);
        $this->assertDatabaseHas('schema_table_columns', [
            'name' => 'name',
            'datatype' => 'varchar',
            'length' => 40,
            'order' => 2
        ]);
    }

    public function testUserCanUpdateSchemaTableColumns()
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

        $data['schema_table_columns'] = [
            'id' => [
                $schemaTableColumns->first()->id,
                $schemaTableColumns->last()->id,
                null, // last row is removed
            ],
            'name' => [
                'user_id',
                'account_id',
            ],
            'datatype' => [
                'bigint',
                'text',
            ],
            'length' => [
                10,
                10,
            ],
            'unsigned' => [
                'true',
                'false',
            ],
            'auto_increment' => [
                'true',
                'false',
            ],
            'primary_key' => [
                'true',
                'false',
            ],
            'unique' => [
                'false',
                'false',
            ],
            'zero_fill' => [
                'false',
                'false',
            ],
            'nullable' => [
                'false',
                'false',
            ],
            'comment' => [
                'yay',
                null,
            ],
            'default_value' => [
                null,
                '1000',
            ],
            'order' => [
                3,
                1,
            ],
        ];

        $this->post(route('schemaTables.updateColumns', ['schemaTable' => $schemaTable->id]), $data)
            ->assertOk()
            ->assertJsonStructure(['status', 'html']);

        $this->assertDatabaseHas('schema_table_columns', [
            'name' => 'user_id',
            'datatype' => 'bigint',
            'length' => 10,
            'order' => 3
        ]);
        $this->assertDatabaseHas('schema_table_columns', [
            'name' => 'account_id',
            'datatype' => 'text',
            'length' => 10,
            'order' => 1
        ]);
    }

    /**
     * @dataProvider invalidSchemaTableColumnProvider
     */
    public function testInvalidSchemaTableColumn($input, $field)
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

        $attributes['schema_table_columns']['id'] = ['null', 'null'];
        $attributes['schema_table_columns'][$field] = [$input];

        $this->post(route('schemaTables.columns', ['schemaTable' => $schemaTable->id]), $attributes)
            ->assertSessionHasErrors($field.'.*');
    }

    public function invalidSchemaTableColumnProvider()
    {
        return [
            ['', 'name'],
            [null, 'name'],
            [Str::random(256), 'name'],
            ['spaced column', 'name'],
            ['', 'datatype'],
            [null, 'datatype'],
            [Str::random(51), 'datatype'],
            ['spaced datatype', 'datatype'],
            ['', 'length'],
            [null, 'length'],
            [Str::random(256), 'length'],
            [Str::random(256), 'default_value'],
            [Str::random(256), 'comment'],
        ];
    }

}
