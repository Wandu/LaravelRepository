<?php
namespace Wandu\Laravel\Repository\DataMapper;

use Illuminate\Support\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /**
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            return $value instanceof DataMapper ? $value->toArray() : $value;
        }, $this->items);
    }
}
