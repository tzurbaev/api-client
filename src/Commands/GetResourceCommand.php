<?php

namespace Zurbaev\ApiClient\Commands;

use Psr\Http\Message\ResponseInterface;
use Zurbaev\ApiClient\Contracts\ApiResourceInterface;

abstract class GetResourceCommand extends ResourceCommand
{
    /**
     * Resource ID.
     */
    protected $resourceId;

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
     * HTTP request method.
     *
     * @return string
     */
    public function requestMethod()
    {
        return 'GET';
    }

    /**
     * Set resource ID.
     *
     * @param mixed $resourceId
     *
     * @return $this
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;

        return $this;
    }

    /**
     * Get resource ID.
     *
     * @return mixed
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * Handle command response.
     *
     * @param ResponseInterface    $response
     * @param ApiResourceInterface $owner
     *
     * @return mixed
     */
    public function handleResponse(ResponseInterface $response, ApiResourceInterface $owner)
    {
        $className = $this->resourceClass();

        return $className::createFromResponse($response, $owner->getApi(), $owner);
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
        $resourcePath = $this->resourcePath().'/'.$this->getResourceId();

        return $resource->apiUrl($resourcePath);
    }
}
