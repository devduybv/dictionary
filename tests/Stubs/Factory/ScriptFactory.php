<?php

use Faker\Generator as Faker;
use VCComponent\Laravel\Script\Entities\Script;

$factory->define(Script::class, function (Faker $faker) {
    return [
        'title' => $faker->words(rand(4, 7), true),
        'position' => $faker->words(rand(1,1), true),
        'status' => 1,
        'content' => $faker->words(rand(4, 7), true),
    ];
});
