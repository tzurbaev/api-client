<?php

namespace Zurbaev\ApiClient\Tests\Helpers;

use Zurbaev\ApiClient\Commands\ListResourcesCommand;

class FakeResourceCommand extends ListResourcesCommand
{
    public function resourcePath()
    {
        return 'foo';
    }

    public function resourceClass()
    {
        return 'FakeResource';
    }

    public function itemsKey()
    {
        return 'bar';
    }
}
