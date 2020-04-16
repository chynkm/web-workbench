<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotCreateRelationship()
    {
        $schema = factory('App\Models\Schema')->create();
        $primaryTable = factory('App\Models\SchemaTable')->create(['schema_id' => $schema->id]);
        $primaryTableColumn = factory('App\Models\SchemaTableColumn')->create(['schema_table_id' => $primaryTable->id]);
        $foreignTable = factory('App\Models\SchemaTable')->create(['schema_id' => $schema->id]);
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create(['schema_table_id' => $foreignTable->id]);
        $user = factory('App\Models\User')->create();
        $attributes = [
            'user_id' => $user->id,
            'primary_table_column_id' => $primaryTableColumn->id,
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $primaryTable->id]), $attributes)
            ->assertRedirect('login');
    }

    public function testGuestCannotUpdateRelationship()
    {
        $relationship = factory('App\Models\Relationship')->create();
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create(['schema_table_id' => $relationship->foreign_table_id]);
        $attributes = [
            'user_id' => $relationship->user_id,
            'primary_table_column_id' => $relationship->primary_table_column_id,
            'foreign_table_id' => $relationship->foreign_table_id,
            'foreign_table_column_id' => $foreignTableColumn->id,
        ];

        $this->post(route('schemaTables.updateColumns', ['schemaTable' => $relationship->primary_table_column_id]), $attributes)
            ->assertRedirect('login');
    }

    public function testGuestCannotDeleteRelationship()
    {
        $relationship = factory('App\Models\Relationship')->create();

        $this->get(route('relationships.delete', ['relationship' => $relationship->id]))
            ->assertRedirect('login');
    }

    public function testUserCanCreateRelationships()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $primaryTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $primaryTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $primaryTable->id,
            'user_id' => Auth::id(),
        ]);

        $foreignTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $foreignTable->id,
            'user_id' => Auth::id(),
        ]);

        $data['relationships'] = [
            'id' => [
                null,
                null, // last row is removed
            ],
            'primary_table_column_id' => [
                $primaryTableColumn->id,
            ],
            'foreign_table_id' => [
                $foreignTable->id,
            ],
            'foreign_table_column_id' => [
                $foreignTableColumn->id,
            ]
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $primaryTable->id]), $data)
            ->assertOk()
            ->assertJsonStructure(['status', 'html']);

        $this->assertDatabaseHas('relationships', [
            'user_id' => Auth::id(),
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
        ]);
    }

    public function testUserCanUpdateRelationships()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $primaryTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $primaryTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $primaryTable->id,
            'user_id' => Auth::id(),
        ]);

        $foreignTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $foreignTable->id,
            'user_id' => Auth::id(),
        ]);

        $relationship = factory('App\Models\Relationship')->create([
            'user_id' => Auth::id(),
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
        ]);

        $foreignTable1 = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $foreignTableColumn1 = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $foreignTable1->id,
            'user_id' => Auth::id(),
        ]);

        $data['relationships'] = [
            'id' => [
                $relationship->id,
                null, // last row is removed
            ],
            'primary_table_column_id' => [
                $primaryTableColumn->id,
            ],
            'foreign_table_id' => [
                $foreignTable1->id,
            ],
            'foreign_table_column_id' => [
                $foreignTableColumn1->id,
            ]
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $primaryTable->id]), $data)
            ->assertOk()
            ->assertJsonStructure(['status', 'html']);

        $this->assertDatabaseHas('relationships', [
            'user_id' => Auth::id(),
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
            'foreign_table_id' => $foreignTable1->id,
            'foreign_table_column_id' => $foreignTableColumn1->id,
        ]);
    }

    public function testUserCanUpdateRelationshipsWithSameInput()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $primaryTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $primaryTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $primaryTable->id,
            'user_id' => Auth::id(),
        ]);

        $foreignTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $foreignTable->id,
            'user_id' => Auth::id(),
        ]);

        $relationship = factory('App\Models\Relationship')->create([
            'user_id' => Auth::id(),
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
        ]);

        $data['relationships'] = [
            'id' => [
                $relationship->id,
                null, // last row is removed
            ],
            'primary_table_column_id' => [
                $primaryTableColumn->id,
            ],
            'foreign_table_id' => [
                $foreignTable->id,
            ],
            'foreign_table_column_id' => [
                $foreignTableColumn->id,
            ]
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $primaryTable->id]), $data)
            ->assertOk()
            ->assertJsonStructure(['status', 'html']);

        $this->assertDatabaseHas('relationships', [
            'user_id' => Auth::id(),
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
        ]);
    }

    /**
     * @dataProvider invalidSaveRelationshipProvider
     */
    public function testInvalidSaveRelationship($input, $field)
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $primaryTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $primaryTable->id]), ['relationships' => $input])
            ->assertSessionHasErrors($field.'.*');
    }

    public function invalidSaveRelationshipProvider()
    {
        return [
            [
                [
                    'id' => [
                        null,
                        null, // last row is removed
                    ],
                    'primary_table_column_id' => [
                        '',
                    ],
                    'foreign_table_id' => [
                        1,
                    ],
                    'foreign_table_column_id' => [
                        1,
                    ]
                ],
                'primary_table_column_id'
            ],
            [
                [
                    'id' => [
                        null,
                        null, // last row is removed
                    ],
                    'primary_table_column_id' => [
                        null,
                    ],
                    'foreign_table_id' => [
                        1,
                    ],
                    'foreign_table_column_id' => [
                        1,
                    ]
                ],
                'primary_table_column_id'
            ],
            [
                [
                    'id' => [
                        null,
                        null, // last row is removed
                    ],
                    'primary_table_column_id' => [
                        1,
                    ],
                    'foreign_table_id' => [
                        null,
                    ],
                    'foreign_table_column_id' => [
                        1,
                    ]
                ],
                'foreign_table_id'
            ],
            [
                [
                    'id' => [
                        null,
                        null, // last row is removed
                    ],
                    'primary_table_column_id' => [
                        1,
                    ],
                    'foreign_table_id' => [
                        '',
                    ],
                    'foreign_table_column_id' => [
                        1,
                    ]
                ],
                'foreign_table_id'
            ],
            [
                [
                    'id' => [
                        null,
                        null, // last row is removed
                    ],
                    'primary_table_column_id' => [
                        1,
                    ],
                    'foreign_table_id' => [
                        1,
                    ],
                    'foreign_table_column_id' => [
                        null,
                    ]
                ],
                'foreign_table_column_id'
            ],
            [
                [
                    'id' => [
                        null,
                        null, // last row is removed
                    ],
                    'primary_table_column_id' => [
                        1,
                    ],
                    'foreign_table_id' => [
                        1,
                    ],
                    'foreign_table_column_id' => [
                        '',
                    ]
                ],
                'foreign_table_column_id'
            ],
        ];
    }

    public function testDifferentDatatypeSaveRelationship()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $primaryTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $primaryTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $primaryTable->id,
            'user_id' => Auth::id(),
        ]);

        $foreignTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $foreignTable->id,
            'user_id' => Auth::id(),
            'datatype' => 'varchar',
        ]);

        $data['relationships'] = [
            'id' => [
                null,
                null, // last row is removed
            ],
            'primary_table_column_id' => [
                $primaryTableColumn->id,
            ],
            'foreign_table_id' => [
                $foreignTable->id,
            ],
            'foreign_table_column_id' => [
                $foreignTableColumn->id,
            ]
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $primaryTable->id]), $data)
            ->assertSessionHasErrors('foreign_table_column_id.*');
    }

    public function testSameColumnSaveRelationship()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $primaryTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $primaryTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $primaryTable->id,
            'user_id' => Auth::id(),
        ]);

        $data['relationships'] = [
            'id' => [
                null,
                null, // last row is removed
            ],
            'primary_table_column_id' => [
                $primaryTableColumn->id,
            ],
            'foreign_table_id' => [
                $primaryTable->id,
            ],
            'foreign_table_column_id' => [
                $primaryTableColumn->id,
            ]
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $primaryTable->id]), $data)
            ->assertSessionHasErrors('foreign_table_column_id.*');
    }

    public function testDifferentSchemasTableSaveRelationship()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $primaryTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $primaryTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $primaryTable->id,
            'user_id' => Auth::id(),
        ]);

        $foreignTable = factory('App\Models\SchemaTable')->create(['user_id' => Auth::id()]);
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $foreignTable->id,
            'user_id' => Auth::id(),
        ]);

        $data['relationships'] = [
            'id' => [
                null,
                null, // last row is removed
            ],
            'primary_table_column_id' => [
                $primaryTableColumn->id,
            ],
            'foreign_table_id' => [
                $foreignTable->id,
            ],
            'foreign_table_column_id' => [
                $foreignTableColumn->id,
            ]
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $primaryTable->id]), $data)
            ->assertSessionHasErrors('foreign_table_id.*');
    }

    public function testUniqueSaveRelationship()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $primaryTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $primaryTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $primaryTable->id,
            'user_id' => Auth::id(),
        ]);

        $foreignTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $foreignTable->id,
            'user_id' => Auth::id(),
        ]);

        $relationship = factory('App\Models\Relationship')->create([
            'user_id' => Auth::id(),
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
        ]);

        $data['relationships'] = [
            'id' => [
                null,
                null, // last row is removed
            ],
            'primary_table_column_id' => [
                $primaryTableColumn->id,
            ],
            'foreign_table_id' => [
                $foreignTable->id,
            ],
            'foreign_table_column_id' => [
                $foreignTableColumn->id,
            ]
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $primaryTable->id]), $data)
            ->assertSessionHasErrors('foreign_table_column_id.*');
    }

    public function testUserCannotDeleteOthersRelationship()
    {
        $this->signIn();
        $relationship = factory('App\Models\Relationship')->create();

        $this->get(route('relationships.delete', ['relationship' => $relationship->id]))
            ->assertRedirect(route('schemas.index'));
    }

    public function testUserDeleteRelationship()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $primaryTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $primaryTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $primaryTable->id,
            'user_id' => Auth::id(),
        ]);

        $foreignTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $foreignTable->id,
            'user_id' => Auth::id(),
        ]);

        $relationship = factory('App\Models\Relationship')->create([
            'user_id' => Auth::id(),
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
        ]);

        $this->get(route('relationships.delete', ['relationship' => $relationship->id]))
            ->assertOk()
            ->assertJsonStructure(['status']);

        $this->assertSoftDeleted('relationships', [
            'id' => $relationship->id,
            'user_id' => Auth::id(),
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
        ]);
    }

}
