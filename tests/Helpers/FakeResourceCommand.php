<?php

namespace Zurbaev\ApiClient\Tests\Helpers;

use Zurbaev\ApiClient\Commands\ResourceCommand;

class FakeResourceCommand extends ResourceCommand
{
    public function resourcePath()
    {
        return 'foo';
    }

    public function resourceClass()
    {
        return 'FakeResource';
    }

    public function listResponseItemsKey()
    {
        return 'bar';
    }
}
