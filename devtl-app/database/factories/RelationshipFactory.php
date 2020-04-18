<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Relationship;
use Faker\Generator as Faker;

$factory->define(Relationship::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\Models\User::class),
        'foreign_table_id' => factory(App\Models\SchemaTable::class),
        'foreign_table_column_id' => function (array $relationship) {
            return factory(App\Models\SchemaTableColumn::class)->create(['schema_table_id' => $relationship['foreign_table_id']]);
        },
        'primary_table_id' => factory(App\Models\SchemaTable::class),
        'primary_table_column_id' => function (array $relationship) {
            return factory(App\Models\SchemaTableColumn::class)->create(['schema_table_id' => $relationship['primary_table_id']]);
        },
    ];
});
