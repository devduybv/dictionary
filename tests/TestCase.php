<?php

namespace VCComponent\Laravel\Dictionary\Test;

use Cviebrock\EloquentSluggable\ServiceProvider;
use Dingo\Api\Provider\LaravelServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use VCComponent\Laravel\Category\Providers\CategoryServiceProvider;
use VCComponent\Laravel\Config\Providers\ConfigServiceProvider;
use VCComponent\Laravel\Dictionary\Providers\DictionaryServiceProvider;
use VCComponent\Laravel\Language\Providers\LanguageServiceProvider;
use VCComponent\Laravel\Menu\Providers\MenuComponentProvider;
use VCComponent\Laravel\Order\Providers\OrderServiceProvider;
use VCComponent\Laravel\Script\Providers\ScriptServiceProvider;
use VCComponent\Laravel\Tag\Providers\TagServiceProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return HaiCS\Laravel\Generator\Providers\GeneratorServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelServiceProvider::class,
            ServiceProvider::class,
            DictionaryServiceProvider::class,
            CategoryServiceProvider::class,
            TagServiceProvider::class,
            MenuComponentProvider::class,
            ConfigServiceProvider::class,
            ScriptServiceProvider::class,
            OrderServiceProvider::class,
            LanguageServiceProvider::class,
        ];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->withFactories(__DIR__ . '/../tests/Stubs/Factory');
        $this->loadMigrationsFrom(__DIR__ . '/../src/database/migrations');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:TEQ1o2POo+3dUuWXamjwGSBx/fsso+viCCg9iFaXNUA=');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('language.supportedLocales', [
            'vi',
            'en',
        ]);
        $app['config']->set('dictionary.post_status', [
            'published',
            'pending',
        ]);
        $app['config']->set('dictionary.product_status', [
            'published',
            'pending',
        ]);
        $app['config']->set('admin-menu', [
            [
                "title" => "dashboard",
                "icon" => ["icon" => "dashboard", "pack" => "social-networks"],
                "modules" => "dashboard",
                "link" => '/admin/dashboard',
            ],
            [
                "title" => "post",
                "icon" => ["icon" => "dashboard", "pack" => "social-networks"],
                "modules" => "dashboard",
                "link" => '/admin/dashboard',
            ],
        ]);
        $app['config']->set('dashboard', [
            'sections' => [
                [
                    "label" => "products",
                    "order" => 2,
                    "widgets" => [
                        [
                            "label" => "order",
                            "type" => "shortcut",
                            "order" => 1,
                            "url" => "/admin/order",
                            "color" => "#f53fc5",
                            "icon" => "/assets/icons/icon-white/product.svg",
                        ],
                    ],
                ],
                [
                    "label" => "setting",
                    "order" => 4,
                    "widgets" => [
                        [
                            "label" => "banner",
                            "type" => "shortcut",
                            "order" => 1,
                            "url" => "#",
                            "color" => "#00b894",
                            "icon" => "/assets/icons/icon-white/banner.svg",
                        ],
                    ],
                ],
            ],
        ]);

        $app['config']->set('admin-setting', [
            'sections' => [
                [
                    "label" => "general settings",
                    "order" => 1,
                    "widgets" => [
                        [
                            "label" => "decentralization",
                            "description" => "description decentralization",
                            "order" => 1,
                            "url" => "/admin/system/roles",
                            "icon" => "/assets/images/icon/Mask Group 257.svg",
                        ],
                    ],
                ],
                [
                    "label" => "product",
                    "order" => 2,
                    "widgets" => [
                        [
                            "label" => "product configuration",
                            "description" => "description product configuration",
                            "order" => 1,
                            "url" => "/admin/products/config-meta",
                            "icon" => "assets/images/icon/Mask Group 262.svg",
                        ],
                    ],
                ],
            ],
        ]);

        $app['config']->set('jwt.secret', '5jMwJkcDTUKlzcxEpdBRIbNIeJt1q5kmKWxa0QA2vlUEG6DRlxcgD7uErg51kbBl');
        $app['config']->set('repository.cache.enabled', false);
        $app['config']->set('category.namespace', 'category-management');

        $app['config']->set('DICTIONARY_COMPONENT_NAMESPACE', 'dictionary-management');

    }
    public function assertValidation($response, $field, $error_message)
    {
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            "errors" => [
                $field => [
                    $error_message,
                ],
            ],
        ]);
    }
}
