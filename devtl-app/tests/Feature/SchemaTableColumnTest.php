<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\TestCase;

class SchemaTableColumnTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /*public function testGuestCannotFetchSchemaTableColumns()
    {
        $this->markTestSkipped('Removed Ajax schemaTableColumn fetch');
        $schema = factory('App\Models\Schema')->create();
        $schemaTable = factory('App\Models\SchemaTable')->create(['schema_id' => $schema->id]);

        $this->get(route('schemaTables.columns', ['schemaTable' => $schemaTable->id]))
            ->assertRedirect('login');
    }*/

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

    public function testGuestCannotDeleteSchemaTableColumns()
    {
        $schemaTableColumn = factory('App\Models\SchemaTableColumn')->create();

        $this->get(route('schemaTableColumns.delete', ['schemaTableColumn' => $schemaTableColumn->id]))
            ->assertRedirect('login');
    }

    /*public function testUserCanViewSchemaTableColumnsListing()
    {
        $this->markTestSkipped('Removed Ajax schemaTableColumn fetch');
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
        $this->markTestSkipped('Removed Ajax schemaTableColumn fetch');
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
    }*/

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
                1,
                0,
            ],
            'auto_increment' => [
                1,
                0,
            ],
            'primary_key' => [
                1,
                0,
            ],
            'unique' => [
                0,
                0,
            ],
            'zero_fill' => [
                0,
                0,
            ],
            'nullable' => [
                0,
                0,
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
                null,
                null, // last row is removed
            ],
            'name' => [
                'user_id',
                $schemaTableColumns->last()->name,
                'account_id',
            ],
            'datatype' => [
                'bigint',
                'text',
                'bigint',
            ],
            'length' => [
                10,
                10,
                20,
            ],
            'unsigned' => [
                1,
                0,
                1,
            ],
            'auto_increment' => [
                1,
                0,
                0,
            ],
            'primary_key' => [
                1,
                0,
                0,
            ],
            'unique' => [
                0,
                0,
                0,
            ],
            'zero_fill' => [
                0,
                0,
                0,
            ],
            'nullable' => [
                0,
                0,
                0,
            ],
            'comment' => [
                'yay',
                null,
                null,
            ],
            'default_value' => [
                null,
                '1000',
                null,
            ],
            'order' => [
                3,
                1,
                4,
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
            'name' => $schemaTableColumns->last()->name,
            'datatype' => 'text',
            'length' => 10,
            'order' => 1
        ]);
        $this->assertDatabaseHas('schema_table_columns', [
            'name' => 'account_id',
            'datatype' => 'bigint',
            'length' => 20,
            'order' => 4
        ]);

        $this->assertDatabaseHas('schema_table_column_histories', [
            'schema_table_column_id' => $schemaTableColumns->first()->id,
            'name' => $schemaTableColumns->first()->name,
            'datatype' => $schemaTableColumns->first()->datatype,
            'length' => $schemaTableColumns->first()->length,
            'order' => $schemaTableColumns->first()->order,
        ]);
        $this->assertDatabaseHas('schema_table_column_histories', [
            'schema_table_column_id' => $schemaTableColumns->last()->id,
            'name' => $schemaTableColumns->last()->name,
            'datatype' => $schemaTableColumns->last()->datatype,
            'length' => $schemaTableColumns->last()->length,
            'order' => $schemaTableColumns->last()->order,
        ]);
    }

    /**
     * @dataProvider invalidSaveSchemaTableColumnProvider
     */
    public function testInvalidSaveSchemaTableColumn($input, $field)
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

        $schemaTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $schemaTable->id,
            'name' => 'id',
        ]);

        $attributes['schema_table_columns']['id'] = ['null', 'null'];
        $attributes['schema_table_columns'][$field] = [$input];

        $this->post(route('schemaTables.updateColumns', ['schemaTable' => $schemaTable->id]), $attributes)
            ->assertSessionHasErrors($field.'.*');
    }

    public function invalidSaveSchemaTableColumnProvider()
    {
        return [
            ['', 'name'],
            [null, 'name'],
            [Str::random(256), 'name'],
            ['spaced column', 'name'],
            ['id', 'name'],
            ['', 'datatype'],
            [null, 'datatype'],
            [Str::random(51), 'datatype'],
            ['spaced datatype', 'datatype'],
            [Str::random(256), 'length'],
            [Str::random(256), 'default_value'],
            [Str::random(256), 'comment'],
            [2, 'nullable'],
            [null, 'nullable'],
            ['', 'nullable'],
            ['a', 'nullable'],
            [2, 'unsigned'],
            [null, 'unsigned'],
            ['', 'unsigned'],
            ['a', 'unsigned'],
            [2, 'unique'],
            [null, 'unique'],
            ['', 'unique'],
            ['a', 'unique'],
            [2, 'auto_increment'],
            [null, 'auto_increment'],
            ['', 'auto_increment'],
            ['a', 'auto_increment'],
            [2, 'primary_key'],
            [null, 'primary_key'],
            ['', 'primary_key'],
            ['a', 'primary_key'],
            [2, 'zero_fill'],
            [null, 'zero_fill'],
            ['', 'zero_fill'],
            ['a', 'zero_fill'],
        ];
    }

    public function testAUserCannotUpdateOtherUsersSchemaTableColumn()
    {
        $this->signIn();
        $schemaTableColumn = factory('App\Models\SchemaTableColumn')->create();

        $attributes['schema_table_columns']['id'] = [
            $schemaTableColumn->id,
            'null'
        ];
        $attributes['schema_table_columns']['name'] = [$schemaTableColumn->first()->name];

        $this->post(route('schemaTables.updateColumns', ['schemaTable' => $schemaTableColumn->schemaTable->id]), $attributes)
            ->assertRedirect(route('schemas.index'));
    }

    public function testUniqueSchemaTableColumnName()
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

        $schemaTableColumns = factory('App\Models\SchemaTableColumn', 2)->create([
            'schema_table_id' => $schemaTable->id,
        ]);

        $attributes['schema_table_columns']['id'] = [
            $schemaTableColumns->first()->id,
            $schemaTableColumns->last()->id,
            'null'
        ];
        $attributes['schema_table_columns']['name'] = [
            $schemaTableColumns->first()->name,
            $schemaTableColumns->first()->name, //using first table name for second table
        ];

        $this->post(route('schemaTables.updateColumns', ['schemaTable' => $schemaTable->id]), $attributes)
            ->assertSessionHasErrors('name.*');
    }

    public function testUserCannotDeleteOthersSchemaTableColumn()
    {
        $this->signIn();
        $schemaTableColumn = factory('App\Models\SchemaTableColumn')->create();

        $this->get(route('schemaTableColumns.delete', ['schemaTableColumn' => $schemaTableColumn->id]))
            ->assertRedirect(route('schemas.index'));
    }

    public function testUserDeleteSchemaTableColumn()
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
        $schemaTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $schemaTable->id,
            'user_id' => Auth::id(),
        ]);

        $this->get(route('schemaTableColumns.delete', ['schemaTableColumn' => $schemaTableColumn->id]))
            ->assertOk()
            ->assertJsonStructure(['status']);

        $this->assertSoftDeleted('schema_table_columns', [
            'id' => $schemaTableColumn->id,
            'user_id' => Auth::id(),
        ]);
    }
}
