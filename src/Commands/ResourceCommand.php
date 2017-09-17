<?php

namespace Zurbaev\ApiClient\Commands;

use Zurbaev\ApiClient\Contracts\ApiResourceInterface;
use Psr\Http\Message\ResponseInterface;

abstract class ResourceCommand extends ApiCommand
{
    /**
     * Resource path.
     *
     * @return string
     */
    abstract public function resourcePath();

    /**
     * Resource class name.
     *
     * @return string
     */
    abstract public function resourceClass();

    /**
     * Handle command response.
     *
     * @param ResponseInterface    $response
     * @param ApiResourceInterface $owner
     *
     * @return mixed
     */
    abstract public function handleResponse(ResponseInterface $response, ApiResourceInterface $owner);

    /**
     * Determines if current command is list command.
     *
     * @return bool
     */
    protected function isListCommand(): bool
    {
        return false;
    }

    /**
     * Command name.
     *
     * @return string
     */
    public function command()
    {
        return $this->resourcePath();
    }

    /**
     * HTTP request URL.
     *
     * @param ApiResourceInterface $resource
     *
     * @return string
     */
    public function requestUrl(ApiResourceInterface $resource)
    {
        $resourcePath = $this->resourcePath();

        return $resource->apiUrl($resourcePath);
    }
}
