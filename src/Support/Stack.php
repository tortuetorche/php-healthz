<?php
namespace Gentux\Healthz\Support;

/**
 * Helper for managing collections of stuffs
 *
 * @package Gentux\Healthz
 */
trait Stack
{

    protected $items = [];

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @param $item
     *
     * @return $this
     */
    public function push($item): self
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @param array $items
     *
     * @return $this
     */
    public function merge(array $items): self
    {
        $this->items = array_merge($this->items, $items);

        return $this;
    }

    /**
     * @param array $items
     *
     * @return $this
     */
    public function replace(array $items): self
    {
        $this->items = $items;

        return $this;
    }
}
