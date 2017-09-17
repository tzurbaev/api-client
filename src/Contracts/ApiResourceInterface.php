<?php

namespace Zurbaev\ApiClient\Contracts;

use GuzzleHttp\ClientInterface;

interface ApiResourceInterface
{
    /**
     * Get API provider.
     *
     * @return ApiProviderInterface
     */
    public function getApi(): ApiProviderInterface;

    /**
     * Get underlying API provider's HTTP client.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient(): ClientInterface;

    /**
     * Resource API URL.
     *
     * @param string $path            = ''
     * @param bool   $withPropagation = true
     *
     * @return string
     */
    public function apiUrl(string $path = '', bool $withPropagation = true): string;
}
