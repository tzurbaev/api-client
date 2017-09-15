<?php

namespace Zurbaev\ApiClient\Traits;

trait AbstractCollection
{
    /**
     * The collection items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * The collection items keys.
     *
     * @var array
     */
    protected $keys = [];

    /**
     * Determines if lazy loading was already initiated.
     *
     * @var bool
     */
    protected $lazyLoadInitiated = false;

    /**
     * Performs lazy iterator items load.
     */
    abstract public function lazyLoad();

    /**
     * Generates item keys.
     */
    abstract public function generateKeys();

    /**
     * Performs lazy load checks and loads items if required.
     */
    protected function checkLazyLoad()
    {
        if ($this->lazyLoadInitiated === true) {
            return;
        }

        $this->lazyLoad();
        $this->generateKeys();

        $this->lazyLoadInitiated = true;
    }

    /**
     * Determines if lazy load was already initiated.
     *
     * @return bool
     */
    protected function lazyLoadInitiated(): bool
    {
        return $this->lazyLoadInitiated === true;
    }
}
