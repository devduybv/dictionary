<?php

use Faker\Generator as Faker;
use VCComponent\Laravel\Menu\Entities\ItemMenu;

$factory->define(ItemMenu::class, function (Faker $faker) {
    return [
        'menu_id' => $faker->words(rand(1,1), true),
        'label' => $faker->words(rand(2,5), true),
        'link' => $faker->words(rand(1,1), true),
        'icon' => null,
        'order_by' => $faker->unique()->randomDigit,
        'type' => $faker->words(rand(1,1), true),
        'parent_id' => 0,
        ];
});
