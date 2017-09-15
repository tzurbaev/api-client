<?php

namespace Zurbaev\ApiClient\Tests\Stubs;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Zurbaev\ApiClient\Contracts\ApiProviderInterface;

class FakeApiProvider implements ApiProviderInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        if (!is_null($this->client)) {
            return $this->client;
        }

        return $this->client = $this->createClient();
    }

    /**
     * @return ClientInterface
     */
    public function createClient()
    {
        return new Client();
    }
}
