<?php
/**
 * Fastly API class.
 *
 */

namespace Fastly;

use Fastly\Adapter\FastlyAdapter;

class Fastly {

  const VERSION = '0.1.0';

  private $adapter;

  /**
   * Fastly API entry point
   *
   * @var string
   */
  private $entryPoint;
  private $service;

  private $error;

  /**
   * Fastly constructor.
   *
   * @param FastlyAdapter $adapter
   * @param string $service
   * @param string $entryPoint
   */
  public function __construct(FastlyAdapter $adapter, $service = '', $entryPoint = 'https://api.fastly.com/') {
    $this->adapter    = $adapter;
    $this->service    = $service;
    $this->entryPoint = $entryPoint;
  }

  /**
   * Send a HTTP request to Fastly API.
   *
   * @param $method
   * @param $uri
   * @param array $options
   *
   * @return array | string Fastly's JSON output.
   */
  public function send($method, $uri, array $options = []) {
    $uri = $this->buildEndpoint($uri);

    // Reset errors
    $this->error = null;

    // Send HTTP req to API
    $result = $this->adapter->send($method, $uri, $options);

    if ($result) {
      return $this->buildOutput($result);
    }
    else {
      if ($this->adapter->getError()) {
        $this->error = $this->adapter->getError();
      }
      return $this->error ?? $this->error;
    }
  }

  /**
   * @param $responses
   * @return array|mixed
   */
  private function buildOutput($responses) {
    $output = [];

    if (!is_array($responses)) {
      $responses = [$responses];
    }

    foreach ($responses as $response) {
      $output += json_decode($response, true);
    }

    return $output;
  }


  /**
   * @param $uri
   * @return array|string
   */
  private function buildEndpoint($uri) {
    if (is_array($uri)) {
      foreach ($uri as $key => $value) {
        $uri[$key] = $this->doBuildEndpoint($value);
      }
    } else {
      $uri = $this->doBuildEndpoint($uri);
    }

    return $uri;
  }

  /**
   * @param $uri
   * @return string
   */
  private function doBuildEndpoint($uri) {
    if (0 !== strpos($uri, 'http')) {
      $uri = $this->entryPoint . $uri;
    }

    return $uri;
  }

  /**
   * @param string $service
   * @return Fastly
   */
  public function setService(string $service): Fastly {
    $this->service = $service;
    return $this;
  }
}