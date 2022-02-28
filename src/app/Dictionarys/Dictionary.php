<?php

namespace VCComponent\Laravel\Dictionary\Dictionarys;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use VCComponent\Laravel\Category\Entities\Category;
use VCComponent\Laravel\Config\Entities\Option;
use VCComponent\Laravel\Language\Languages\Facades\Language;
use VCComponent\Laravel\Menu\Entities\Menu;
use VCComponent\Laravel\Order\Entities\OrderStatus;
use VCComponent\Laravel\Script\Entities\Script;
use VCComponent\Laravel\Tag\Entities\Tag;

class Dictionary
{

    public $entityCategory;
    public $entityTag;
    public $entityMenu;
    public $entityScript;
    public $entityOption;
    public $entityOrderStatus;

    public function __construct()
    {
        if (!empty(config('dictionary.models'))) {
            $this->entityCategory = App::make(config('dictionary.models.category'));
            $this->entityTag = App::make(config('dictionary.models.tag'));
            $this->entityScript = App::make(config('dictionary.models.script'));
            $this->entityMenu = App::make(config('dictionary.models.menu'));
            $this->entityOption = App::make(config('dictionary.models.option'));
            $this->entityOrderStatus = App::make(config('dictionary.models.orderStatus'));

        } else {
            $this->entityCategory = new Category;
            $this->entityTag = new Tag;
            $this->entityScript = new Script;
            $this->entityMenu = new Menu;
            $this->entityOption = new Option;
            $this->entityOrderStatus = new OrderStatus;
        }
    }
    public function getPublicData()
    {
        $data = [];
        $data['menus'] = Dictionary::menu();
        $data['option'] = Dictionary::option();
        $data['script'] = Dictionary::script();
        $data['category'] = Dictionary::category();
        $data['tag'] = Dictionary::tag();
        $data['order-status'] = Dictionary::orderStatus();
        $data['locale'] = Language::getSupportedLocales();
        $data['post-status'] = Dictionary::postStatus();
        $data['product-status'] = Dictionary::productStatus();
        return $data;
    }
    public function getPrivateData()
    {
        $data = [];
        $data['admin-menu'] = Dictionary::adminMenu();
        $data['dashboard'] = Dictionary::dashboard();
        $data['admin-setting'] = Dictionary::adminSetting();
        $data['tag'] = Dictionary::tag();
        $data['order-status'] = Dictionary::orderStatus();
        $data['category'] = Dictionary::category();
        $data['locale'] = Language::getSupportedLocales();
        $data['post-status'] = Dictionary::postStatus();
        $data['product-status'] = Dictionary::productStatus();
        return $data;

    }
    public function menu()
    {
        if (Cache::has('menu')) {
            return Cache::get('menu');
        } else {
            $menu = $this->entityMenu->all();
            Cache::forever('menu', $menu);
            return Cache::get('menu');
        }
    }
    public function postStatus()
    {
        $postStatus = collect(config('dictionary.post_status'))->map(function ($item) {
            $item = __($item);
            return $item;
        });

        return $postStatus;
    }
    public function adminMenu()
    {
        $admin_menu = collect(config('admin-menu'))->map(function ($item) {
            $item['title'] = __($item['title']);
            if (!empty($item['children'])) {
                $children = collect($item['children'])->map(function ($children) {
                    $children['title'] = __($children['title']);
                    return $children;
                });
                $item['children'] = $children;
            }
            return $item;
        });
        return $admin_menu;

    }
    public function dashboard()
    {
        $dashboard['sections'] = collect(config('dashboard.sections'))->map(function ($item) {
            $item['label'] = __($item['label']);
            if (!empty($item['widgets'])) {
                $widget = collect($item['widgets'])->map(function ($widget) {
                    $widget['label'] = __($widget['label']);
                    return $widget;
                });
                $item['widgets'] = $widget;
            }
            return $item;
        });
        return $dashboard['sections'];

    }
    public function adminSetting()
    {
        $admin_setting['sections'] = collect(config('admin-setting.sections'))->map(function ($item) {
            $item['label'] = __($item['label']);
            if (!empty($item['widgets'])) {
                $widget = collect($item['widgets'])->map(function ($widget) {
                    $widget['label'] = __($widget['label']);
                    $widget['description'] = __($widget['description']);
                    return $widget;
                });
                $item['widgets'] = $widget;
            }
            return $item;
        });
        return $admin_setting['sections'];

    }
    public function productStatus()
    {
        $productStatus = collect(config('dictionary.product_status'))->map(function ($item) {
            $item = __($item);
            return $item;
        });
        return $productStatus;
    }
    public function orderStatus()
    {
        if (Cache::has('orderStatus')) {
            return Cache::get('orderStatus');
        } else {
            $orderStatus = $this->entityOrderStatus->all();
            Cache::forever('orderStatus', $orderStatus);
            return Cache::get('orderStatus');
        }
    }
    public function menuItem($menu)
    {
        if (Cache::has('menuItem')) {
            return Cache::get('menuItem');
        } else {
            $menuItem = $menu->menuItems;
            Cache::forever('menuItem', $menuItem);
            return Cache::get('menuItem');
        }
    }
    public function subMenuItem($itemMenu)
    {
        if (Cache::has('subMenuItem')) {
            return Cache::get('subMenuItem');
        } else {

            $menuItem = $itemMenu->subMenus;
            Cache::forever('subMenuItem', $menuItem);
            return Cache::get('subMenuItem');
        }
    }
    public function script()
    {
        if (Cache::has('script')) {
            return Cache::get('script');
        } else {
            $script = $this->entityScript->all();
            Cache::forever('script', $script);
            return Cache::get('script');
        }
    }
    public function findScript($field, $value)
    {
        return Dictionary::script()->where($field, $value)->first();
    }
    public function option()
    {
        if (Cache::has('option')) {
            return Cache::get('option');
        } else {
            $option = $this->entityOption->all();
            Cache::forever('option', $option);
            return Cache::get('option');
        }
    }
    public function tag()
    {
        if (Cache::has('tag')) {
            return Cache::get('tag');
        } else {
            $tag = $this->entityTag->all();
            Cache::forever('tag', $tag);
            return Cache::get('tag');
        }
    }
    public function findOption($key)
    {
        $option = Dictionary::option()->where('key', $key)->first();
        if (!empty($option)) {
            return $option->value;
        }
    }
    public function findArrayOption($key)
    {
        return Dictionary::option()->whereIn('key', $key);
    }
    public function category()
    {
        if (Cache::has('category')) {
            return Cache::get('category');
        } else {
            $category = $this->entityCategory->all();
            Cache::forever('category', $category);
            return Cache::get('category');
        }
    }
    public function findCategory($field, $value)
    {
        return Dictionary::category()->where($field, $value)->first();
    }
    public function categoryQuery(array $where, $number = 10, $order_by = 'order', $order = 'asc')
    {
        $query = Dictionary::category();
        foreach ($where as $key => $value) {
            $query = $query->where($key, $value);
        }
        if ($order === 'asc') {
            $query = $query->sortBy($order_by);
        } elseif ($order === 'desc') {
            $query = $query->sortByDesc($order_by);
        }
        if ($number > 0) {
            return $query->take($number);
        }
        return $query;
    }
    public function categoryQueryPaginate(array $where, $number = 10, $page = 1, $order_by = 'order', $order = 'asc')
    {
        $query = Dictionary::category();
        foreach ($where as $key => $value) {
            $query = $query->where($key, $value);
        }
        if ($order === 'asc') {
            $query = $query->sortBy($order_by);
        } elseif ($order === 'desc') {
            $query = $query->sortByDesc($order_by);
        }
        return $query->forPage($page, $number);

    }
    public function findTag($field, $value)
    {
        return Dictionary::tag()->where($field, $value)->first();
    }
    public function tagQuery(array $where, $number = 10, $order_by = 'order', $order = 'asc')
    {
        $query = Dictionary::tag();
        foreach ($where as $key => $value) {
            $query = $query->where($key, $value);
        }
        if ($order === 'asc') {
            $query = $query->sortBy($order_by);
        } elseif ($order === 'desc') {
            $query = $query->sortByDesc($order_by);
        }
        if ($number > 0) {
            return $query->take($number);
        }
        return $query;
    }
    public function tagQueryPaginate(array $where, $number = 10, $order_by = 'order', $order = 'asc', $page = 1)
    {
        $query = Dictionary::tag();
        foreach ($where as $key => $value) {
            $query = $query->where($key, $value);
        }
        if ($order === 'asc') {
            $query = $query->sortBy($order_by);
        } elseif ($order === 'desc') {
            $query = $query->sortByDesc($order_by);
        }
        return $query->forPage($page, $number);

    }
}
