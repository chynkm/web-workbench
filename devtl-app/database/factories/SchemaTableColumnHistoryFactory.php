<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SchemaTableColumnHistory;
use Faker\Generator as Faker;

$factory->define(SchemaTableColumnHistory::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\Models\User::class),
        'schema_table_id' => factory(App\Models\SchemaTable::class),
        'schema_table_column_id' => function (array $schemaTableColumnHistory) {
            return factory(App\Models\SchemaTableColumn::class)->create(['schema_table_id' => $schemaTableColumnHistory['schema_table_id']]);
        },
        'name' => $faker->word,
        'datatype' => config('env.first_column_datatype'),
        'length' => config('env.first_column_length'),
        'primary_key' => 0,
        'unique' => 0,
        'zero_fill' => 0,
        'auto_increment' => 0,
        'unsigned' => 0,
        'nullable' => 1,
        'order' => $faker->randomDigit,
    ];
});
