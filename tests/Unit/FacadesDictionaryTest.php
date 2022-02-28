<?php

namespace VCComponent\Laravel\Dictionary\Test\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\Category\Entities\Category;
use VCComponent\Laravel\Config\Entities\Option;
use VCComponent\Laravel\Dictionary\Dictionarys\Facades\Dictionary;
use VCComponent\Laravel\Dictionary\Test\TestCase;
use VCComponent\Laravel\Menu\Entities\ItemMenu;
use VCComponent\Laravel\Menu\Entities\Menu;
use VCComponent\Laravel\Order\Entities\OrderStatus;
use VCComponent\Laravel\Script\Entities\Script;
use VCComponent\Laravel\Tag\Entities\Tag;

class FacadesDictionaryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_get_category_all()
    {
        $categories = factory(Category::class, 3)->create();
        $data = Dictionary::category();
        $this->assertSame($categories[0]->id, $data[0]->id);
        $this->assertCount(3, $data);
    }
    /**
     * @test
     */
    public function should_find_category()
    {
        factory(Category::class)->create(['name' => 'category 1']);
        factory(Category::class)->create(['name' => 'category 2']);
        $data = Dictionary::findCategory('slug', 'category-2');
        $this->assertSame('category 2', $data->name);
        $this->assertNotEquals('category 1', $data->name);
    }
    /**
     * @test
     */
    public function should_get_category()
    {
        factory(Category::class)->create(['type' => 'service']);
        factory(Category::class, 3)->create(['type' => 'about']);
        $data = Dictionary::categoryQuery(['type' => 'service']);
        $this->assertSame('service', $data[0]->type);
        $this->assertNotEquals('about', $data[0]->type);
        $this->assertCount(1, $data);
    }
    /**
     * @test
     */
    public function should_get_category_paginate()
    {
        factory(Category::class, 3)->create(['type' => 'service']);
        factory(Category::class, 4)->create(['type' => 'about']);
        $data = Dictionary::categoryQueryPaginate(['type' => 'service'], 2, 1);
        $this->assertSame('service', $data[0]->type);
        $this->assertNotEquals('about', $data[0]->type);
        $this->assertCount(2, $data);
    }
    /**
     * @test
     */
    public function should_get_menu_all()
    {
        $menus = factory(Menu::class, 2)->create();
        $data = Dictionary::menu();
        $this->assertSame($menus[0]->name, $data[0]->name);
        $this->assertCount(2, $data);
    }
    /**
     * @test
     */
    public function should_get_menu_item()
    {
        $menu_header = factory(Menu::class)->create(['page' => 'menu-header']);
        factory(Menu::class)->create();
        $menu_item = factory(ItemMenu::class, 2)->create(['link' => 'menu-header', 'menu_id' => 1, 'type' => 'menu-header']);
        factory(ItemMenu::class, 2)->create(['menu_id' => 2]);
        $data = Dictionary::menuItem($menu_header);
        $this->assertSame($menu_item[0]->link, $data[0]->link);
        $this->assertCount(2, $data);
    }

    /**
     * @test
     */
    public function should_get_sub_menu_item()
    {
        $menu_item = factory(ItemMenu::class)->create(['link' => 'menu-header', 'menu_id' => 1, 'type' => 'menu-header']);
        $sub_menu_item = factory(ItemMenu::class, 2)->create(['parent_id' => 1, 'link' => 'menu-header', 'menu_id' => 1, 'type' => 'menu-header']);
        $data = Dictionary::subMenuItem($menu_item);
        $this->assertSame($sub_menu_item[0]->link, $data[0]->link);
        $this->assertSame($sub_menu_item[1]->link, $data[1]->link);
        $this->assertCount(2, $sub_menu_item);

    }

    /**
     * @test
     */
    public function should_get_script_all()
    {
        $scripts = factory(Script::class, 3)->create();
        $data = Dictionary::script();
        $this->assertSame($scripts[0]->id, $data[0]->id);
        $this->assertCount(3, $data);

    }
    /**
     * @test
     */
    public function should_find_script()
    {
        factory(Script::class)->create(['title' => 'ma nhung 1']);
        factory(Script::class)->create(['title' => 'ma nhung 2']);
        $data = Dictionary::findScript('title', 'ma nhung 1');
        $this->assertSame('ma nhung 1', $data->title);
        $this->assertNotEquals('ma nhung 2', $data->title);
    }

    /**
     * @test
     */
    public function should_get_option_all()
    {
        $options = factory(Option::class, 3)->create();
        $data = Dictionary::option();
        $this->assertSame($options[0]->id, $data[0]->id);
        $this->assertCount(3, $data);
    }

    /**
     * @test
     */
    public function should_find_option()
    {
        factory(Option::class)->create(['key' => 'key1', 'value' => 'value 1']);
        factory(Option::class)->create(['key' => 'key2', 'value' => 'value 2']);
        $data = Dictionary::findOption('key1');
        $this->assertSame('value 1', $data);
        $this->assertNotEquals('value 2', $data);
    }
    /**
     * @test
     */
    public function should_find_option_array_key()
    {
        factory(Option::class)->create(['key' => 'key1', 'value' => 'value 1']);
        factory(Option::class)->create(['key' => 'key2', 'value' => 'value 2']);
        factory(Option::class)->create(['key' => 'key3', 'value' => 'value 3']);
        $data = Dictionary::findArrayOption(['key1', 'key2']);
        $this->assertSame('value 1', $data[0]->value);
        $this->assertSame('value 2', $data[1]->value);
        $this->assertCount(2, $data);
    }
    /**
     * @test
     */
    public function should_get_tag_all()
    {
        $tags = factory(Tag::class, 3)->create();
        $data = Dictionary::tag();
        $this->assertSame($tags[0]->id, $data[0]->id);
        $this->assertCount(3, $data);

    }
    /**
     * @test
     */
    public function should_find_tag()
    {
        factory(Tag::class)->create(['name' => 'tag 1']);
        factory(Tag::class)->create(['name' => 'tag 2']);
        $data = Dictionary::findTag('slug', 'tag-2');
        $this->assertSame('tag 2', $data->name);
        $this->assertNotEquals('tag 1', $data->name);
    }
    /**
     * @test
     */
    public function should_get_tag()
    {
        factory(Tag::class)->create(['status' => 1, 'name' => 'tag 1']);
        factory(Tag::class)->create(['status' => 1, 'name' => 'tag 2']);
        factory(Tag::class)->create(['status' => 2, 'name' => 'tag 3']);

        $data = Dictionary::tagQuery(['status' => 1]);
        $this->assertSame('tag 1', $data[0]->name);
        $this->assertSame('tag 2', $data[1]->name);
        $this->assertCount(2, $data);
    }
    /**
     * @test
     */
    public function should_get_tag_paginate()
    {
        factory(Tag::class)->create(['status' => 1, 'name' => 'tag 1']);
        factory(Tag::class)->create(['status' => 1, 'name' => 'tag 2']);
        factory(Tag::class)->create(['status' => 1, 'name' => 'tag 3']);
        factory(Tag::class, 4)->create(['status' => 2]);
        $data = Dictionary::tagQueryPaginate(['status' => 1], 2, 1);
        $this->assertSame('tag 1', $data[0]->name);
        $this->assertSame('tag 2', $data[1]->name);
        $this->assertCount(2, $data);
    }
    /**
     * @test
     */
    public function should_get_public_data()
    {
        $this->should_get_menu_all();
        $this->should_get_option_all();
        $this->should_get_script_all();
        $this->should_get_category_all();
        $this->should_get_tag_all();
        $orderStatus = factory(OrderStatus::class, 2)->create();
        $data = Dictionary::getPublicData();
        $this->assertSame($orderStatus[0]->id, $data['order-status'][0]->id);
        $this->assertCount(2, $data['order-status']);

        $this->assertSame(['vi', 'en'], $data['locale']);

        $this->assertSame('published', $data['post-status'][0]);
        $this->assertCount(2, $data['post-status']);

        $this->assertSame('published', $data['product-status'][0]);
        $this->assertCount(2, $data['product-status']);

    }
    /**
     * @test
     */
    public function should_get_private_data()
    {
        $this->should_get_category_all();
        $this->should_get_tag_all();
        $orderStatus = factory(OrderStatus::class, 2)->create();
        $data = Dictionary::getPrivateData();
        $this->assertSame('dashboard', $data['admin-menu'][0]['title']);
        $this->assertSame('post', $data['admin-menu'][1]['title']);
        $this->assertCount(2, $data['admin-menu']);

        $this->assertSame('products', $data['dashboard'][0]['label']);
        $this->assertSame('setting', $data['dashboard'][1]['label']);
        $this->assertCount(2, $data['dashboard']);

        $this->assertSame('general settings', $data['admin-setting'][0]['label']);
        $this->assertSame('product', $data['admin-setting'][1]['label']);
        $this->assertCount(2, $data['admin-setting']);

        $this->assertSame($orderStatus[0]->id, $data['order-status'][0]->id);
        $this->assertCount(2, $data['order-status']);

        $this->assertSame(['vi', 'en'], $data['locale']);

        $this->assertSame('published', $data['post-status'][0]);
        $this->assertCount(2, $data['post-status']);

        $this->assertSame('published', $data['product-status'][0]);
        $this->assertCount(2, $data['product-status']);
    }

}
