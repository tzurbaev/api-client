<?php

namespace Zurbaev\ApiClient;

use ArrayAccess;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Zurbaev\ApiClient\Contracts\ApiProviderInterface;
use Zurbaev\ApiClient\Traits\ArrayAccessTrait;
use Zurbaev\ApiClient\Contracts\ApiResourceInterface;
use Zurbaev\ApiClient\Exceptions\Resources\DeleteResourceException;
use Zurbaev\ApiClient\Exceptions\Resources\UpdateResourceException;

abstract class ApiResource implements ArrayAccess, ApiResourceInterface
{
    use ArrayAccessTrait;

    /**
     * @var ApiProviderInterface
     */
    protected $api;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var ApiResourceInterface
     */
    protected $owner;

    /**
     * Create new resource instance.
     *
     * @param ApiProviderInterface $api   = null
     * @param array                $data  = []
     * @param ApiResourceInterface $owner
     */
    public function __construct(ApiProviderInterface $api = null, array $data = [], ApiResourceInterface $owner = null)
    {
        $this->api = $api;
        $this->data = $data;
        $this->owner = $owner;
    }

    /**
     * Resource type.
     *
     * @return string
     */
    abstract public static function resourceType();

    /**
     * Resource path (relative to owner or API root).
     *
     * @return string
     */
    abstract public function resourcePath();

    /**
     * Create new Resource instance from HTTP response.
     *
     * @param ResponseInterface    $response
     * @param ApiProviderInterface $api
     * @param ApiResourceInterface $owner    = null
     *
     * @return static
     */
    public static function createFromResponse(ResponseInterface $response, ApiProviderInterface $api, ApiResourceInterface $owner = null)
    {
        $json = json_decode((string) $response->getBody(), true);
        $resourceType = static::resourceType();

        if (empty($json[$resourceType])) {
            static::throwNotFoundException();
        }

        return new static($api, $json[$resourceType], $owner);
    }

    /**
     * Throw HTTP Not Found exception.
     *
     * @throws \InvalidArgumentException
     */
    protected static function throwNotFoundException()
    {
        throw new \InvalidArgumentException('Given response is not a '.static::resourceType().' response.');
    }

    /**
     * Determines if current resource has an owner.
     *
     * @return bool
     */
    public function hasResourceOwner(): bool
    {
        return !is_null($this->owner);
    }

    /**
     * Get current resource owner.
     *
     * @return ApiResourceInterface|null
     */
    public function resourceOwner()
    {
        return $this->owner;
    }

    /**
     * Get API provider.
     *
     * @return ApiProviderInterface
     */
    public function getApi(): ApiProviderInterface
    {
        return $this->api;
    }

    /**
     * Get underlying API provider's HTTP client.
     *
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->api->getClient();
    }

    /**
     * Get resource data.
     *
     * @param string|int $key
     * @param mixed      $default = null
     *
     * @return mixed|null
     */
    public function getData($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Resource API URL.
     *
     * @param string $path            = ''
     * @param bool   $withPropagation = true
     *
     * @return string
     */
    public function apiUrl(string $path = '', bool $withPropagation = true): string
    {
        $path = ($path ? '/'.ltrim($path, '/') : '');
        $resourcePath = rtrim($this->resourcePath(), '/').'/'.$this->id().$path;

        if (!$this->hasResourceOwner() || !$withPropagation) {
            return $resourcePath;
        }

        return $this->resourceOwner()->apiUrl($resourcePath);
    }

    /**
     * Resource ID.
     *
     * @return mixed
     */
    public function id()
    {
        return $this->getData('id', 0);
    }

    /**
     * Resource name.
     *
     * @return string|null
     */
    public function name()
    {
        return $this->getData('name');
    }

    /**
     * Resource status.
     *
     * @return string|null
     */
    public function status()
    {
        return $this->getData('status');
    }

    /**
     * Get resource creation date.
     *
     * @return string|null
     */
    public function createdAt()
    {
        return $this->getData('created_at');
    }

    /**
     * Update resource data.
     *
     * @param array $payload
     *
     * @throws UpdateResourceException
     *
     * @return bool
     */
    public function update(array $payload): bool
    {
        $resourceType = static::resourceType();
        $response = null;

        try {
            $response = $this->getHttpClient()->request('PUT', $this->apiUrl(), [
                'json' => $payload,
            ]);
        } catch (RequestException $e) {
            $this->throwResourceException($e->getResponse(), 'update', UpdateResourceException::class);
        }

        $json = json_decode((string) $response->getBody(), true);

        if (empty($json[$resourceType])) {
            return false;
        }

        $this->data = $json[$resourceType];

        return true;
    }

    /**
     * Delete current resource.
     *
     * @throws DeleteResourceException
     *
     * @return bool
     */
    public function delete()
    {
        try {
            $this->getHttpClient()->request('DELETE', $this->apiUrl());
        } catch (RequestException $e) {
            $this->throwResourceException($e->getResponse(), 'delete', DeleteResourceException::class);
        }

        return true;
    }

    /**
     * Throw resource exception.
     *
     * @param ResponseInterface $response
     * @param string            $action
     * @param string            $exceptionClass
     *
     * @throws \Exception
     */
    protected function throwResourceException(ResponseInterface $response, string $action, string $exceptionClass)
    {
        $message = 'Unable to '.$action.' resource (type: '.static::resourceType().', ID: '.$this->id().').';

        if (is_null($response)) {
            throw new \InvalidArgumentException($message);
        }

        $message .= ' Server response: "'.((string) $response->getBody()).'".';

        throw new $exceptionClass($message, $response->getStatusCode());
    }
}
