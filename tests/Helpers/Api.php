<?php

namespace Zurbaev\ApiClient\Tests\Helpers;

use GuzzleHttp\Client;
use Zurbaev\ApiClient\Tests\Stubs\FakeApiProvider;
use Zurbaev\ApiClient\Tests\Stubs\FakeResource;

class Api
{
    /**
     * @param callable|null $callback
     *
     * @return FakeResource
     */
    public static function fakeResource(callable $callback = null)
    {
        $api = \Mockery::mock(FakeApiProvider::class.'[createClient]');
        $http = \Mockery::mock(Client::class);

        if (is_callable($callback)) {
            call_user_func($callback, $http);
        }

        $api->shouldReceive('createClient')->andReturn($http);

        return new FakeResource($api);
    }
}
