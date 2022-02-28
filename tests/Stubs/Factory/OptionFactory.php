<?php

use Faker\Generator as Faker;
use VCComponent\Laravel\Config\Entities\Option;

$factory->define(Option::class, function (Faker $faker) {
    return [
        'label' => $faker->words(rand(4, 7), true),
        'key' => $faker->words(rand(1,1), true),
        'value' => $faker->words(rand(4, 7), true),
    ];
});
