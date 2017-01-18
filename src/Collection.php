<?php

namespace Consigliere\Components;

use Illuminate\Contracts\Support\Arrayable;

class Collection extends \Illuminate\Support\Collection
{
    /**
     * Get items collections.
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            if ($value instanceof Component) {
                return $value->json()->getAttributes();
            }

            return $value instanceof Arrayable ? $value->toArray() : $value;

        }, $this->items);
    }
}
