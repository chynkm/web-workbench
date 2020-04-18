<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SchemaTableColumnHistory;
use Faker\Generator as Faker;

$factory->define(SchemaTableColumnHistory::class, function (Faker $faker) {
    return [
        'schema_table_column_id' => factory(App\Models\SchemaTableColumn::class),
        'user_id' => function(array $schemaTableColumnHistory) {
            return App\Models\SchemaTableColumn::find($schemaTableColumnHistory['schema_table_column_id'])->user_id;
        },
        'schema_table_id' => function(array $schemaTableColumnHistory) {
            return App\Models\SchemaTableColumn::find($schemaTableColumnHistory['schema_table_column_id'])->schema_table_id;
        },
        'name' => function(array $schemaTableColumnHistory) {
            return App\Models\SchemaTableColumn::find($schemaTableColumnHistory['schema_table_column_id'])->name;
        },
        'datatype' => function(array $schemaTableColumnHistory) {
            return App\Models\SchemaTableColumn::find($schemaTableColumnHistory['schema_table_column_id'])->datatype;
        },
        'length' => function(array $schemaTableColumnHistory) {
            return App\Models\SchemaTableColumn::find($schemaTableColumnHistory['schema_table_column_id'])->length;
        },
        'primary_key' => function(array $schemaTableColumnHistory) {
            return App\Models\SchemaTableColumn::find($schemaTableColumnHistory['schema_table_column_id'])->primary_key;
        },
        'unique' => function(array $schemaTableColumnHistory) {
            return App\Models\SchemaTableColumn::find($schemaTableColumnHistory['schema_table_column_id'])->unique;
        },
        'zero_fill' => function(array $schemaTableColumnHistory) {
            return App\Models\SchemaTableColumn::find($schemaTableColumnHistory['schema_table_column_id'])->zero_fill;
        },
        'auto_increment' => function(array $schemaTableColumnHistory) {
            return App\Models\SchemaTableColumn::find($schemaTableColumnHistory['schema_table_column_id'])->auto_increment;
        },
        'unsigned' => function(array $schemaTableColumnHistory) {
            return App\Models\SchemaTableColumn::find($schemaTableColumnHistory['schema_table_column_id'])->unsigned;
        },
        'nullable' => function(array $schemaTableColumnHistory) {
            return App\Models\SchemaTableColumn::find($schemaTableColumnHistory['schema_table_column_id'])->nullable;
        },
        'order' => function(array $schemaTableColumnHistory) {
            return App\Models\SchemaTableColumn::find($schemaTableColumnHistory['schema_table_column_id'])->order;
        },
    ];
});
