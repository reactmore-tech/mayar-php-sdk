<?php

namespace ReactMoreTech\MayarHeadlessAPI\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use ReactMoreTech\MayarHeadlessAPI\Auth\Auth;
use ReactMoreTech\MayarHeadlessAPI\Exceptions\ResponseException;

/**
 * The Guzzle adapter class provides an implementation of the Adapter interface using the Guzzle HTTP client library.
 */
class Guzzle implements AdapterInterface
{
    /**
     * The Guzzle client instance.
     *
     * @var Client
     */
    private $client;

    const PRODUCTION_BASE_URI = 'https://api.mayar.id/';

    const SANDBOX_BASE_URI = 'https://api.mayar.club/';

    /**
     * Create a new Guzzle instance.
     *
     * @param Auth $auth The authorization instance.
     * @param bool $isProduction A flag indicating whether to use production environment or not.
     * @param string|null $baseURI The base URI of the API.
     * @return void
     */
    public function __construct(Auth $auth, bool $isProduction, ?string $baseURI = null)
    {
        if ($baseURI === null) {
            $baseURI = $isProduction ? self::PRODUCTION_BASE_URI : self::SANDBOX_BASE_URI;
        }

        $headers = $auth->getHeaders();

        $this->client = new Client([
            'base_uri' => $baseURI,
            'headers'  => $headers,
            'Accept'   => 'application/x-www-form-urlencoded',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $uri, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->request('get', $uri, $data, $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function post(string $uri, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->request('post', $uri, $data, $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function put(string $uri, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->request('put', $uri, $data, $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function patch(string $uri, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->request('patch', $uri, $data, $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $uri, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->request('delete', $uri, $data, $headers);
    }

    /**
     * Send an HTTP request.
     *
     * @param string $method The HTTP request method.
     * @param string $uri The URI of the API endpoint.
     * @param array $data The request payload.
     * @param array $headers The HTTP request headers.
     * @throws InvalidArgumentException If an invalid HTTP request method is specified.
     * @throws ResponseException If an error occurs while sending the HTTP request.
     * @return mixed
     */
    public function request(string $method, string $uri, array $data = [], array $headers = [])
    {
        if (!in_array($method, ['get', 'post', 'put', 'patch', 'delete'], true)) {
            throw new InvalidArgumentException('Request method must be get, post, put, patch, or delete');
        }

        $options = [
            'headers' => $headers,
        ];

        if ($method === 'get') {
            $options['query'] = $data;
        } elseif ($method === 'post' || $method === 'put' || $method === 'patch' || $method === 'delete') {
            if (isset($data['json'])) {
                $options['json'] = $data['json'];
            } elseif (isset($data['multipart'])) {
                $options['multipart'] = [];
                foreach ($data['multipart'] as $name => $content) {
                    $options['multipart'][] = [
                        'name' => $name,
                        'contents' => $content,
                    ];
                }
            } else {
                $options['form_params'] = $data;
            }
        }

        return $this->client->{$method}($uri, $options);
    }
}
