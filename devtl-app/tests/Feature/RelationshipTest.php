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
            'foreign_table_column_id' => $foreignTableColumn->id,
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $foreignTable->id]), $attributes)
            ->assertRedirect('login');
    }

    public function testGuestCannotUpdateRelationship()
    {
        $relationship = factory('App\Models\Relationship')->create();
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create(['schema_table_id' => $relationship->foreign_table_id]);
        $attributes = [
            'user_id' => $relationship->user_id,
            'foreign_table_column_id' => $relationship->primary_table_column_id,
            'primary_table_id' => $relationship->foreign_table_id,
            'primary_table_column_id' => $foreignTableColumn->id,
        ];

        $this->post(route('schemaTables.updateColumns', ['schemaTable' => $relationship->foreign_table_id]), $attributes)
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
        $foreignTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $foreignTable->id,
            'user_id' => Auth::id(),
        ]);
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
            'foreign_table_column_id' => [
                $foreignTableColumn->id,
            ],
            'primary_table_id' => [
                $primaryTable->id,
            ],
            'primary_table_column_id' => [
                $primaryTableColumn->id,
            ]
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $foreignTable->id]), $data)
            ->assertOk()
            ->assertJsonStructure(['status', 'html']);

        $this->assertDatabaseHas('relationships', [
            'user_id' => Auth::id(),
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
        ]);
    }

    public function testUserCanUpdateRelationships()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $foreignTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $foreignTable->id,
            'user_id' => Auth::id(),
        ]);

        $primaryTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $primaryTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $primaryTable->id,
            'user_id' => Auth::id(),
        ]);

        $relationship = factory('App\Models\Relationship')->create([
            'user_id' => Auth::id(),
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
        ]);

        $primaryTable1 = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $primaryTableColumn1 = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $primaryTable1->id,
            'user_id' => Auth::id(),
        ]);

        $data['relationships'] = [
            'id' => [
                $relationship->id,
                null, // last row is removed
            ],
            'foreign_table_column_id' => [
                $foreignTableColumn->id,
            ],
            'primary_table_id' => [
                $primaryTable1->id,
            ],
            'primary_table_column_id' => [
                $primaryTableColumn1->id,
            ]
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $foreignTable->id]), $data)
            ->assertOk()
            ->assertJsonStructure(['status', 'html']);

        $this->assertDatabaseHas('relationships', [
            'id' => $relationship->id,
            'user_id' => Auth::id(),
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
            'primary_table_id' => $primaryTable1->id,
            'primary_table_column_id' => $primaryTableColumn1->id,
        ]);

        $this->assertDatabaseHas('relationship_histories', [
            'relationship_id' => $relationship->id,
            'user_id' => Auth::id(),
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
        ]);
    }

    public function testUserCanUpdateRelationshipsWithSameInput()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $foreignTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $foreignTable->id,
            'user_id' => Auth::id(),
        ]);

        $primaryTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $primaryTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $primaryTable->id,
            'user_id' => Auth::id(),
        ]);

        $relationship = factory('App\Models\Relationship')->create([
            'user_id' => Auth::id(),
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
        ]);

        $data['relationships'] = [
            'id' => [
                $relationship->id,
                null, // last row is removed
            ],
            'foreign_table_column_id' => [
                $foreignTableColumn->id,
            ],
            'primary_table_id' => [
                $primaryTable->id,
            ],
            'primary_table_column_id' => [
                $primaryTableColumn->id,
            ]
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $foreignTable->id]), $data)
            ->assertOk()
            ->assertJsonStructure(['status', 'html']);

        $this->assertDatabaseHas('relationships', [
            'user_id' => Auth::id(),
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
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
        $foreignTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $foreignTable->id]), ['relationships' => $input])
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
                    'foreign_table_column_id' => [
                        '',
                    ],
                    'primary_table_id' => [
                        1,
                    ],
                    'primary_table_column_id' => [
                        1,
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
                    'foreign_table_column_id' => [
                        null,
                    ],
                    'primary_table_id' => [
                        1,
                    ],
                    'primary_table_column_id' => [
                        1,
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
                    'foreign_table_column_id' => [
                        1,
                    ],
                    'primary_table_id' => [
                        null,
                    ],
                    'primary_table_column_id' => [
                        1,
                    ]
                ],
                'primary_table_id'
            ],
            [
                [
                    'id' => [
                        null,
                        null, // last row is removed
                    ],
                    'foreign_table_column_id' => [
                        1,
                    ],
                    'primary_table_id' => [
                        '',
                    ],
                    'primary_table_column_id' => [
                        1,
                    ]
                ],
                'primary_table_id'
            ],
            [
                [
                    'id' => [
                        null,
                        null, // last row is removed
                    ],
                    'foreign_table_column_id' => [
                        1,
                    ],
                    'primary_table_id' => [
                        1,
                    ],
                    'primary_table_column_id' => [
                        null,
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
                    'foreign_table_column_id' => [
                        1,
                    ],
                    'primary_table_id' => [
                        1,
                    ],
                    'primary_table_column_id' => [
                        '',
                    ]
                ],
                'primary_table_column_id'
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
        $foreignTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $foreignTable->id,
            'user_id' => Auth::id(),
            'datatype' => 'varchar',
        ]);

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
            'foreign_table_column_id' => [
                $primaryTableColumn->id,
            ],
            'primary_table_id' => [
                $foreignTable->id,
            ],
            'primary_table_column_id' => [
                $foreignTableColumn->id,
            ]
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $foreignTable->id]), $data)
            ->assertSessionHasErrors('primary_table_column_id.*');
    }

    public function testSameColumnSaveRelationship()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
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
            'foreign_table_column_id' => [
                $foreignTableColumn->id,
            ],
            'primary_table_id' => [
                $foreignTable->id,
            ],
            'primary_table_column_id' => [
                $foreignTableColumn->id,
            ]
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $foreignTable->id]), $data)
            ->assertSessionHasErrors('primary_table_column_id.*');
    }

    public function testDifferentSchemasTableSaveRelationship()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $foreignTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $foreignTable->id,
            'user_id' => Auth::id(),
        ]);

        $primaryTable = factory('App\Models\SchemaTable')->create(['user_id' => Auth::id()]);
        $primaryTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $primaryTable->id,
            'user_id' => Auth::id(),
        ]);

        $data['relationships'] = [
            'id' => [
                null,
                null, // last row is removed
            ],
            'foreign_table_column_id' => [
                $foreignTableColumn->id,
            ],
            'primary_table_id' => [
                $primaryTable->id,
            ],
            'primary_table_column_id' => [
                $primaryTableColumn->id,
            ]
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $foreignTable->id]), $data)
            ->assertSessionHasErrors('primary_table_id.*');
    }

    public function testSavedRelationshipAreUnique()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $foreignTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $foreignTable->id,
            'user_id' => Auth::id(),
        ]);

        $primaryTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $primaryTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $primaryTable->id,
            'user_id' => Auth::id(),
        ]);

        $relationship = factory('App\Models\Relationship')->create([
            'user_id' => Auth::id(),
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
        ]);

        $data['relationships'] = [
            'id' => [
                null,
                null, // last row is removed
            ],
            'foreign_table_column_id' => [
                $foreignTableColumn->id,
            ],
            'primary_table_id' => [
                $primaryTable->id,
            ],
            'primary_table_column_id' => [
                $primaryTableColumn->id,
            ]
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $foreignTable->id]), $data)
            ->assertSessionHasErrors('primary_table_column_id.*');
    }

    public function testOneColumnCanHaveOnlyOneRelationship()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()
            ->schemas()
            ->sync([$schema->id]);
        $foreignTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $foreignTable->id,
            'user_id' => Auth::id(),
        ]);

        $primaryTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $primaryTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $primaryTable->id,
            'user_id' => Auth::id(),
        ]);

        $relationship = factory('App\Models\Relationship')->create([
            'user_id' => Auth::id(),
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
        ]);

        $primaryTable1 = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $primaryTableColumn1 = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $primaryTable1->id,
            'user_id' => Auth::id(),
        ]);

        $data['relationships'] = [
            'id' => [
                null,
                null, // last row is removed
            ],
            'foreign_table_column_id' => [
                $foreignTableColumn->id,
            ],
            'primary_table_id' => [
                $primaryTable1->id,
            ],
            'primary_table_column_id' => [
                $primaryTableColumn1->id,
            ]
        ];

        $this->post(route('schemaTables.updateRelationships', ['schemaTable' => $foreignTable->id]), $data)
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
        $foreignTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $foreignTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $foreignTable->id,
            'user_id' => Auth::id(),
        ]);

        $primaryTable = factory('App\Models\SchemaTable')->create([
            'schema_id' => $schema->id,
            'user_id' => Auth::id(),
        ]);
        $primaryTableColumn = factory('App\Models\SchemaTableColumn')->create([
            'schema_table_id' => $primaryTable->id,
            'user_id' => Auth::id(),
        ]);

        $relationship = factory('App\Models\Relationship')->create([
            'user_id' => Auth::id(),
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
        ]);

        $this->get(route('relationships.delete', ['relationship' => $relationship->id]))
            ->assertOk()
            ->assertExactJson(['status' => true]);

        $this->assertSoftDeleted('relationships', [
            'id' => $relationship->id,
            'user_id' => Auth::id(),
            'foreign_table_id' => $foreignTable->id,
            'foreign_table_column_id' => $foreignTableColumn->id,
            'primary_table_id' => $primaryTable->id,
            'primary_table_column_id' => $primaryTableColumn->id,
        ]);
    }

}
