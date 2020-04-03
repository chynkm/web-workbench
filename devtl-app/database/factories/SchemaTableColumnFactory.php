<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SchemaTableColumn;
use Faker\Generator as Faker;

$factory->define(SchemaTableColumn::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\Models\User::class),
        'schema_table_id' => factory(App\Models\SchemaTable::class),
        'name' => $faker->word,
        'datatype' => config('env.first_column_datatype'),
        'length' => config('env.first_column_length'),
        'order' => $faker->randomDigit,
    ];
});
