<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Schema;
use Faker\Generator as Faker;

$factory->define(Schema::class, function (Faker $faker) {
    return [
        'name' => config('env.first_schema_name'),
    ];
});

$factory->afterCreating(App\Models\Schema::class, function ($schema, $faker) {
    $user = factory('App\Models\User')->create();
    $user->schemas()
        ->sync([$schema->id => ['owner' => true]]);
});

