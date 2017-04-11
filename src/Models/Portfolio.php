<?php
namespace Sabian\Models;

use Sabian\Helpers\Resource;

class Portfolio
{
    protected $items;

    protected $resourceName = 'portfolio';

    public function __construct()
    {
        $this->items = Resource::load($this->resourceName);
    }

    /**
     * Get last portfolio item
     *
     * @return array
     */
    public function getLatestItem()
    {
        return end($this->items);
    }

    /**
     * Get N items without last
     *
     * @param int $count Items count
     * @return array
     */
    public function getPreviousItems($count = 3)
    {
        return array_reverse( array_slice($this->items, -($count+1), $count) );
    }

    public function getAllItems()
    {
        return array_reverse($this->items);
    }

    public function getItem($id)
    {
        $index = array_search($id, array_column($this->items, 'id'));

        return $index !== false ? $this->items[$index] : false;
    }
}