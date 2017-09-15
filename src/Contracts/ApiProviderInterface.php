<?php

namespace Zurbaev\ApiClient\Contracts;

use GuzzleHttp\ClientInterface;

interface ApiProviderInterface
{
    /**
     * Get HTTP client.
     *
     * @return ClientInterface
     */
    public function getClient(): ClientInterface;
}
