<?php

namespace Zurbaev\ApiClient\Traits\Commands;

use Psr\Http\Message\ResponseInterface;
use Zurbaev\ApiClient\Contracts\ApiResourceInterface;

trait RawBodyResponseTrait
{
    /**
     * Handle command response.
     *
     * @param ResponseInterface    $response
     * @param ApiResourceInterface $owner
     *
     * @return string
     */
    public function handleResponse(ResponseInterface $response, ApiResourceInterface $owner)
    {
        return (string) $response->getBody();
    }
}
