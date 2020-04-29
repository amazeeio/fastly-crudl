<?php

namespace Fastly\Adapter;

use Fastly\Fastly as Fastly;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Pool;
use GuzzleHttp\Exception\RequestException;

class FastlyAdapter {
  private $error = [];
  private $options = [];

  private $output = [];
  /**
   * @var null|string
   */
  private $fastlyKey;

  /**
   * @param string $fastlyKey
   * @param array  $defaultHeaders
   */
  public function __construct ($fastlyKey = null, $defaultHeaders = []) {
    /** @var array $defaultHeaders */
    $this->options = array_merge(
      ['headers' => [
        'Fastly-Key' => $fastlyKey,
        'Accept'     => 'application/json',
        'User-Agent' => 'fastly-php-v' . Fastly::VERSION
      ]],
      $defaultHeaders
    );
    $this->fastlyKey = $fastlyKey;
  }

  /**
   * Send HTTP Response.
   *
   * @param $method
   * @param $uri
   * @param array $options
   *
   * @return array Response
   */
  public function send($method, $uri, array $options = []) {
    $this->error = [];
    $this->output = [];

    if (!is_array($uri)) {
      $uri = [$uri];
    }

    $client = new Client();

    $requests = function ($urls) use ($method, $options) {
      foreach ($urls as $url) {
        yield new Request($method, $url, array_merge_recursive($options, $this->options)['headers']);
      }
    };

    $pool = new Pool($client, $requests($uri), [
      'concurrency' => 100,
      'fulfilled' => function (ResponseInterface $response) {
        $this->output[] = $this->getBody($response);
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
  public function getError() {
    return implode("\r\n", $this->error);
  }

  /**
   * @param ResponseInterface $response
   * @return string
   */
  private function getBody(ResponseInterface $response) {
    return (string)$response->getBody();
  }

}