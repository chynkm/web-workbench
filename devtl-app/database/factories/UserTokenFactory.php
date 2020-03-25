<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\UserToken;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(UserToken::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\Models\User::class),
        'token' => Str::random(20),
    ];
});
