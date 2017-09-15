<?php

namespace Zurbaev\ApiClient\Tests;

use Zurbaev\ApiClient\Tests\Helpers\Api;
use Zurbaev\ApiClient\Tests\Helpers\FakeResourceCommand;
use Zurbaev\ApiClient\Tests\Helpers\FakeResponse;
use PHPUnit\Framework\TestCase;
use Zurbaev\ApiClient\Tests\Stubs\FakeApiProvider;
use Zurbaev\ApiClient\Tests\Stubs\FakeResource;

class ResourcesTest extends TestCase
{
    public function testResourcesShouldProperlyGenerateUrls()
    {
        $api = new FakeApiProvider();

        $parentResource = new FakeResource($api, ['id' => 123]);
        $this->assertSame('fake-resource/123', $parentResource->apiUrl());

        $resource = new FakeResource($api, ['id' => 456], $parentResource);
        $this->assertSame('fake-resource/123/fake-resource/456', $resource->apiUrl());
    }

    public function testResourceCommandShouldThrowExceptionIfResourceDataIsMissing()
    {
        $server = Api::fakeResource(function ($http) {
            $http->shouldReceive('request')->andReturn(
                FakeResponse::fake()->withJson([])->toResponse()
            );
        });

        $command = new FakeResourceCommand();

        $this->expectException(\InvalidArgumentException::class);
        $command->from($server);
    }

    public function testResourceCommandShouldReturnCorrectListIfResourceDataContainsEmptyArray()
    {
        $server = Api::fakeResource(function ($http) {
            $http->shouldReceive('request')->andReturn(
                FakeResponse::fake()->withJson(['bar' => []])->toResponse()
            );
        });

        $command = new FakeResourceCommand();
        $result = $command->from($server);

        $this->assertInternalType('array', $result);
        $this->assertSame(0, count($result));
    }
}
