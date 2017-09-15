<?php

namespace Zurbaev\ApiClient\Commands;

use Zurbaev\ApiClient\Contracts\ApiResourceInterface;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

abstract class ResourceCommand extends ApiCommand
{
    /**
     * Resource ID.
     */
    protected $resourceId;

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
     * Determines if current command is list command.
     *
     * @return bool
     */
    protected function isListCommand(): bool
    {
        return method_exists($this, 'listResponseItemsKey');
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
     * HTTP request method.
     *
     * @return string
     */
    public function requestMethod()
    {
        if ($this->isListCommand()) {
            return 'GET';
        }

        return is_null($this->getResourceId()) ? 'POST' : 'GET';
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

        if (!is_null($this->getResourceId())) {
            $resourcePath .= '/'.$this->getResourceId();
        }

        return $resource->apiUrl($resourcePath);
    }

    /**
     * Set resource ID.
     *
     * @param int|string $resourceId
     *
     * @return static
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;

        return $this;
    }

    /**
     * Get resource ID.
     *
     * @return int|string|null
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
        if ($this->isListCommand()) {
            return $this->handleListCommandResponse($response, $owner);
        }

        $className = $this->resourceClass();

        return $className::createFromResponse($response, $owner->getApi(), $owner);
    }

    /**
     * List response handler.
     *
     * @param ResponseInterface    $response
     * @param ApiResourceInterface $owner
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function handleListCommandResponse(ResponseInterface $response, ApiResourceInterface $owner)
    {
        $itemsKey = $this->listResponseItemsKey();

        $json = json_decode((string) $response->getBody(), true);

        if (!isset($json[$itemsKey])) {
            throw new InvalidArgumentException('Given response is not a '.$this->resourcePath().' response.');
        }

        $items = [];
        $className = $this->resourceClass();

        foreach ($json[$itemsKey] as $item) {
            $items[] = new $className($owner->getApi(), $item, $owner);
        }

        return $items;
    }
}
