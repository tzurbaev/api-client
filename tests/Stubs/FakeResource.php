<?php

namespace Zurbaev\ApiClient\Tests\Stubs;

use Zurbaev\ApiClient\ApiResource;

class FakeResource extends ApiResource
{
    public static function resourceType()
    {
        return 'fake';
    }

    public function resourcePath()
    {
        return 'fake-resource';
    }
}
