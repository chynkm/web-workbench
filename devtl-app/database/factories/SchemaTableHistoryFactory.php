<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SchemaTableHistory;
use Faker\Generator as Faker;

$factory->define(SchemaTableHistory::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\Models\User::class),
        'schema_id' => factory(App\Models\Schema::class),
        'schema_table_id' => function (array $schemaTableHistory) {
            return factory(App\Models\SchemaTable::class)->create(['schema_id' => $schemaTableHistory['schema_id']]);
        },
        'name' => $faker->word,
        'engine' => config('env.first_table_engine'),
        'collation' => config('env.first_table_collation'),
        'x_index' => 10,
        'y_index' => 10,
        'height' => 100,
        'width' => 250,
    ];
});
