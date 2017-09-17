<?php

namespace Zurbaev\ApiClient\Traits;

/**
 * Trait LazyIterator
 *
 * @property array $keys
 * @property array $items
 * @property int $position
 */
trait LazyIterator
{
    /**
     * Performs lazy load checks and loads items if required.
     */
    abstract protected function checkLazyLoad();

    /**
     * Current iterator item.
     *
     * @return mixed|null
     */
    public function current()
    {
        $this->checkLazyLoad();

        $currentKey = $this->keys[$this->position];

        return isset($this->items[$currentKey]) ? $this->items[$currentKey] : null;
    }

    /**
     * Current iterator position.
     *
     * @return mixed
     */
    public function key()
    {
        $this->checkLazyLoad();

        return $this->keys[$this->position];
    }

    /**
     * Increments iterator position.
     */
    public function next()
    {
        $this->checkLazyLoad();

        $this->position++;
    }

    /**
     * Rewinds iterator back to first position.
     */
    public function rewind()
    {
        $this->checkLazyLoad();

        $this->position = 0;
    }

    /**
     * Determines if there is some value at current iterator position.
     *
     * @return bool
     */
    public function valid()
    {
        $this->checkLazyLoad();

        if (!isset($this->keys[$this->position])) {
            return false;
        }

        $currentKey = $this->keys[$this->position];

        return isset($this->items[$currentKey]);
    }
}
