<?php

use Faker\Generator as Faker;
use VCComponent\Laravel\Order\Entities\OrderStatus;
use Illuminate\Support\Str;

$factory->define(OrderStatus::class, function (Faker $faker) {
    $name = $faker->words(rand(4, 7), true);
    $slug = Str::slug($name);
    return [
        'name' => $name,
        'slug' => $slug,
        'is_active' => 1,
        'status_id' => $faker->unique()->randomDigit,
    ];
});
