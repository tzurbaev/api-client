<?php

namespace Zurbaev\ApiClient\Traits\Commands;

use Psr\Http\Message\ResponseInterface;
use Zurbaev\ApiClient\Contracts\ApiResourceInterface;

trait BooleanResponseTrait
{
    /**
     * Handle command response.
     *
     * @param ResponseInterface    $response
     * @param ApiResourceInterface $owner
     *
     * @return bool
     */
    public function handleResponse(ResponseInterface $response, ApiResourceInterface $owner)
    {
        return true;
    }
}
