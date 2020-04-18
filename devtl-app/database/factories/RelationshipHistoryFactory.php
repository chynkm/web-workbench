<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\RelationshipHistory;
use Faker\Generator as Faker;

$factory->define(RelationshipHistory::class, function (Faker $faker) {
    return [
        'relationship_id' => factory(App\Models\Relationship::class),
        'user_id' => function(array $relationshipHistory) {
            return App\Models\Relationship::find($relationshipHistory['relationship_id'])->user_id;
        },
        'foreign_table_id' => function(array $relationshipHistory) {
            return App\Models\Relationship::find($relationshipHistory['relationship_id'])->foreign_table_id;
        },
        'foreign_table_column_id' => function(array $relationshipHistory) {
            return App\Models\Relationship::find($relationshipHistory['relationship_id'])->foreign_table_column_id;
        },
        'primary_table_id' => function(array $relationshipHistory) {
            return App\Models\Relationship::find($relationshipHistory['relationship_id'])->primary_table_id;
        },
        'primary_table_column_id' => function(array $relationshipHistory) {
            return App\Models\Relationship::find($relationshipHistory['relationship_id'])->primary_table_column_id;
        },
    ];
});

