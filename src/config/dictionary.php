<?php

return [

    'namespace' => env('DICTIONARY_COMPONENT_NAMESPACE', 'dictionary-management'),

    'models' => [
        'category' => VCComponent\Laravel\Category\Entities\Category::class,
        'tag' => VCComponent\Laravel\Tag\Entities\Tag::class,
        'menu' => VCComponent\Laravel\Menu\Entities\Menu::class,
        'script' => VCComponent\Laravel\Script\Entities\Script::class,
        'option' => VCComponent\Laravel\Config\Entities\Option::class,
        'orderStatus' => VCComponent\Laravel\Order\Entities\OrderStatus::class,
    ],

    'auth_middleware' => [
        'admin' => [
            'middleware' => '',
            'except' => [],
        ],
        'frontend' => [
            'middleware' => '',
            'except' => [],
        ],
    ],
    'post_status' => [
        'post-status.published',
        'post-status.pending',
        'post-status.draft',
        'post-status.recycle-bin',
    ],
    'product_status' => [
        'product-status.published',
        'product-status.pending',
        'product-status.draft',
    ],

];
