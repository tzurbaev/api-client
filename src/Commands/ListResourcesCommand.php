<?php

namespace Zurbaev\ApiClient\Commands;

use Psr\Http\Message\ResponseInterface;
use Zurbaev\ApiClient\Contracts\ApiResourceInterface;

abstract class ListResourcesCommand extends ResourceCommand
{
    /**
     * Get the payload's items key.
     *
     * @return string
     */
    abstract protected function itemsKey();

    /**
     * Determines if current command is list command.
     *
     * @return bool
     */
    public function isListCommand(): bool
    {
        return true;
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
     * Handle command response.
     *
     * @param ResponseInterface    $response
     * @param ApiResourceInterface $owner
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function handleResponse(ResponseInterface $response, ApiResourceInterface $owner)
    {
        $json = json_decode((string) $response->getBody(), true);
        $data = $this->getItemsFromJsonResponse($json);

        if (is_null($data)) {
            throw new \InvalidArgumentException('Given response is not a '.$this->resourcePath().' response.');
        }

        return $this->createResourcesFromJsonData($data, $response, $owner);
    }

    /**
     * Get the items list from given JSON response.
     *
     * @param array $json
     *
     * @return array|null
     */
    protected function getItemsFromJsonResponse(array $json)
    {
        return $json[$this->itemsKey()] ?? null;
    }

    /**
     * Create resources list from given JSON response.
     *
     * @param array                $data
     * @param ResponseInterface    $response
     * @param ApiResourceInterface $owner
     *
     * @return array
     */
    protected function createResourcesFromJsonData(array $data, ResponseInterface $response, ApiResourceInterface $owner)
    {
        $items = [];
        $className = $this->resourceClass();

        foreach ($data as $item) {
            $items[] = new $className($owner->getApi(), $item, $owner);
        }

        return $items;
    }
}
