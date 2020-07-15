<?php

namespace Fastly\Request;

use Fastly\Fastly as Fastly;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Pool;
use GuzzleHttp\Exception\RequestException;

class FastlyRequest
{
    private $entryPoint;
    private $options = [];
    private $client;
    private $token;

    public $output = [];
    public $error = [];
    public $statusCode = [];

    /**
     * @param string $token
     * @param array  $defaultHeaders
     */
    public function __construct($token, $entrypoint, $defaultHeaders = [])
    {
        $this->client = new Client();
        $this->token = $token;
        $this->entryPoint = $entrypoint;

        $this->options = array_merge(
            ['headers' => [
                'Fastly-Key' => $token,
                'Accept'     => 'application/vnd.api+json',
                'User-Agent' => 'fastly-php-wrapper-v' . Fastly::VERSION
            ]],
            $defaultHeaders
        );
    }

    /**
     * Send HTTP Request.
     *
     * @param $method
     * @param $uri
     * @param array $options
     *
     * @return array Response
     */
    public function send($method, $uri, array $options = [])
    {
        $this->error = [];
        $this->output = [];

        if (!is_array($uri)) {
            $uri = [$uri];
        }

        $requests = function ($urls) use ($method, $options) {
            foreach ($urls as $url) {
                yield new Request(
                    $method,
                    $url,
                    array_merge_recursive($options, $this->options)['headers'],
                    ($method === 'POST' || $method === 'PATCH') ? json_encode($options) : null
                );
            }
        };

        $pool = new Pool($this->client, $requests($uri), [
            'concurrency' => 100,
            'fulfilled' => function (ResponseInterface $response) {
                $body = $this->get_body($response);
                $this->statusCode[] = $response->getStatusCode();

                if (!empty($body)) {
                    $this->output[] = $body;
                }
            },
            'rejected' => function (RequestException $e) {
                $this->error[] = $e->getMessage();
            },
        ]);

        // Initiate the transfers and create a promise.
        $promise = $pool->promise();

        // Force the pool of requests to complete.
        $promise->wait();

        return $this->output;
    }

    /**
     * @return string
     */
    public function get_error()
    {
        return implode("\r\n", $this->error);
    }

    /**
     * @param ResponseInterface $response
     * @return string
     */
    private function get_body(ResponseInterface $response)
    {
        if ($response->getStatusCode() === 204) {
            return $response->getReasonPhrase();
        } else {
            return (string) $response->getBody();
        }
    }

    /**
     * Build and format JSON response.
     *
     * @param $responses
     * @return array|mixed
     */
    public function build_output($responses)
    {
        $output = [];

        if (!is_array($responses)) {
            $responses = [$responses];
        }

        foreach ($responses as $response) {
            // Convert JSON to associative php array.
            $output += json_decode($response, true);
        }

        return $output;
    }

    /**
     * Generate endpoint from uri.
     *
     * @param $uri
     * @return array|string
     */
    public function build_endpoint($uri)
    {
        if (is_array($uri)) {
            foreach ($uri as $key => $value) {
                $uri[$key] = $this->do_build_endpoint($value);
            }
        } else {
            $uri = $this->do_build_endpoint($uri);
        }

        return $uri;
    }

    /**
     * Endpoint adjustments.
     *
     * @param $uri
     * @return string
     */
    public function do_build_endpoint($uri)
    {
        if (0 !== strpos($uri, 'http')) {
            $uri = $this->entryPoint . $uri;
        }

        return $uri;
    }
}
