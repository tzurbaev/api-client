<?php

namespace Zurbaev\ApiClient\Commands;

trait NotSupportingResourceClassTrait
{
    /**
     * Server resource class name.
     *
     * @throws \LogicException
     *
     * @return string
     */
    public function resourceClass()
    {
        throw new \LogicException(get_class($this).' does not support resource classes.');
    }
}
