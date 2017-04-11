<?php
namespace Sabian\Models;

use Sabian\Helpers\Resource;

class Testimonials {

    protected $items;

    protected $resourceName = 'testimonials';

    public function __construct()
    {
        $this->items = Resource::load($this->resourceName);
    }

    public function getAllItems()
    {
        return array_reverse($this->items);
    }

    public function random($count = 1)
    {
        $testimonials = array_filter($this->items, function ($val) {
            return $val['in_sidebar'];
        });

        $key = array_rand($testimonials, $count);

        return $this->items[$key];
    }
}