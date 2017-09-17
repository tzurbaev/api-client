<?php

namespace Zurbaev\ApiClient\Helpers;

use GuzzleHttp\Psr7\Response;

class FakeResponse
{
    /**
     * Response body.
     *
     * @var mixed
     */
    protected $body;

    /**
     * HTTP Status.
     *
     * @var int
     */
    protected $status = 200;

    /**
     * HTTP Headers.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * HTTP Version.
     *
     * @var string
     */
    protected $httpVersion = '1.1';

    /**
     * Reason phrase.
     */
    protected $reason;

    /**
     * Creates new fake HTTP Response.
     *
     * @return $this
     */
    public static function fake()
    {
        return new static();
    }

    /**
     * Sets HTTP response status.
     *
     * @param int $status
     *
     * @return $this
     */
    public function withStatus(int $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Sets HTTP response body.
     *
     * @param string|null|resource|\Psr\Http\Message\StreamInterface $body
     *
     * @return $this
     */
    public function withBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Sets response's JSON content.
     *
     * @param array $json
     *
     * @return $this
     */
    public function withJson(array $json)
    {
        $this->body = json_encode($json);

        return $this;
    }

    /**
     * Sets response's HTTP headers.
     *
     * @param array $headers
     *
     * @return $this
     */
    public function withHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Sets HTTP version.
     *
     * @param string $version
     *
     * @return $this
     */
    public function withHttpVersion(string $version)
    {
        $this->httpVersion = $version;

        return $this;
    }

    /**
     * Sets Reason Phrase.
     *
     * @param string $reason
     *
     * @return $this
     */
    public function withReason(string $reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Generates HTTP Response.
     *
     * @return Response
     */
    public function toResponse(): Response
    {
        return new Response(
            $this->status,
            $this->headers,
            $this->body,
            $this->httpVersion,
            $this->reason
        );
    }
}
