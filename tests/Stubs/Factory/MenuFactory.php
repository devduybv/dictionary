<?php

use Faker\Generator as Faker;
use VCComponent\Laravel\Menu\Entities\Menu;

$factory->define(Menu::class, function (Faker $faker) {
    return [
        'name' => $faker->words(rand(1,1), true),
        'status' => 1,
        'position' => 'position-1',
        'page' => 'footer'
    ];
});
