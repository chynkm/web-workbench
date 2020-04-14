<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SchemaTable;
use Faker\Generator as Faker;

$factory->define(SchemaTable::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\Models\User::class),
        'schema_id' => factory(App\Models\Schema::class),
        'name' => $faker->word,
        'engine' => config('env.first_table_engine'),
        'collation' => config('env.first_table_collation'),
    ];
});
