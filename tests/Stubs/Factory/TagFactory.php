<?php

use Faker\Generator as Faker;
use VCComponent\Laravel\Tag\Entities\Tag;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'name' => $faker->words(rand(4, 7), true),
        'status' => 1,
    ];
});
