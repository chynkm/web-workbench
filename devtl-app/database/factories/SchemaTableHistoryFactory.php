<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SchemaTableHistory;
use Faker\Generator as Faker;

$factory->define(SchemaTableHistory::class, function (Faker $faker) {
    return [
        'schema_table_id' => factory(App\Models\SchemaTable::class),
        'user_id' => function(array $schemaTableHistory) {
            return App\Models\SchemaTable::find($schemaTableHistory['schema_table_id'])->user_id;
        },
        'schema_id' => function(array $schemaTableHistory) {
            return App\Models\SchemaTable::find($schemaTableHistory['schema_table_id'])->schema_id;
        },
        'name' => function(array $schemaTableHistory) {
            return App\Models\SchemaTable::find($schemaTableHistory['schema_table_id'])->name;
        },
        'engine' => function(array $schemaTableHistory) {
            return App\Models\SchemaTable::find($schemaTableHistory['schema_table_id'])->engine;
        },
        'collation' => function(array $schemaTableHistory) {
            return App\Models\SchemaTable::find($schemaTableHistory['schema_table_id'])->collation;
        },
        'x_index' => function(array $schemaTableHistory) {
            return App\Models\SchemaTable::find($schemaTableHistory['schema_table_id'])->x_index;
        },
        'y_index' => function(array $schemaTableHistory) {
            return App\Models\SchemaTable::find($schemaTableHistory['schema_table_id'])->y_index;
        },
        'height' => function(array $schemaTableHistory) {
            return App\Models\SchemaTable::find($schemaTableHistory['schema_table_id'])->height;
        },
        'width' => function(array $schemaTableHistory) {
            return App\Models\SchemaTable::find($schemaTableHistory['schema_table_id'])->width;
        },
    ];
});

