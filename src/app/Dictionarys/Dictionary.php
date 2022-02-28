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
    public function clearCache() {
        Cache::forget('publicData');
        Cache::forget('privateData');
    }
    public function getPublicData()
    {
        $data = [];

        if (Cache::has('publicData')) {
            $data = Cache::get('publicData');
        } else {
            $data['menus'] = Dictionary::menu();
            $data['option'] = Dictionary::option();
            $data['script'] = Dictionary::script();
            $data['category'] = Dictionary::category();
            $data['tag'] = Dictionary::tag();
            $data['order-status'] = Dictionary::orderStatus();
            $data['locale'] = Language::getSupportedLocales();
            $data['post-status'] = Dictionary::postStatus();
            $data['product-status'] = Dictionary::productStatus();
            Cache::forever('publicData', $data);
            $data = Cache::get('publicData');
        }
        return $data;
    }
    public function getPrivateData()
    {
        $data = [];
        if (Cache::has('privateData')) {
            $data = Cache::get('privateData');
        } else {
            $data['admin-menu'] = Dictionary::adminMenu();
            $data['dashboard'] = Dictionary::dashboard();
            $data['admin-setting'] = Dictionary::adminSetting();
            $data['tag'] = Dictionary::tag();
            $data['order-status'] = Dictionary::orderStatus();
            $data['category'] = Dictionary::category();
            $data['locale'] = Language::getSupportedLocales();
            $data['post-status'] = Dictionary::postStatus();
            $data['product-status'] = Dictionary::productStatus();
            Cache::forever('privateData', $data);
            $data = Cache::get('privateData');
        }
        return $data;

    }
    public function menu()
    {
        $menu = $this->entityMenu->all();
        return $menu;
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
        $orderStatus = $this->entityOrderStatus->all();
        return $orderStatus;
    }
    public function menuItem($menu)
    {
        $menuItem = $menu->menuItems;
        return $menuItem;
    }
    public function subMenuItem($itemMenu)
    {
        $menuItem = $itemMenu->subMenus;
        return $menuItem;
    }
    public function script()
    {
        $script = $this->entityScript->all();
        return $script;
    }
    public function findScript($field, $value)
    {
        $data = Dictionary::getPublicData();
        return $data['script']->where($field, $value)->first();
    }
    public function option()
    {
        $option = $this->entityOption->all();
        return $option;
    }
    public function tag()
    {
        $tag = $this->entityTag->all();
        return $tag;
    }
    public function findOption($key)
    {
        $data = Dictionary::getPublicData();
        $option = $data['option']->where('key', $key)->first();
        if (!empty($option)) {
            return $option->value;
        }
    }
    public function findArrayOption($key)
    {
        $data = Dictionary::getPublicData();
        return $data['option']->whereIn('key', $key);
    }
    public function category()
    {
        $category = $this->entityCategory->all();
        return $category;
    }
    public function findCategory($field, $value)
    {
        $data = Dictionary::getPublicData();
        return $data['category']->where($field, $value)->first();
    }
    public function categoryQuery(array $where, $number = 10, $order_by = 'order', $order = 'asc')
    {
        $data = Dictionary::getPublicData();
        $query = $data['category'];
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
        $data = Dictionary::getPublicData();
        $query = $data['category'];
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
        $data = Dictionary::getPublicData();
        return $data['tag']->where($field, $value)->first();
    }
    public function tagQuery(array $where, $number = 10, $order_by = 'order', $order = 'asc')
    {
        $data = Dictionary::getPublicData();
        $query = $data['tag'];
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
        $data = Dictionary::getPublicData();
        $query = $data['tag'];
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
