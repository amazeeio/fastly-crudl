<?php
/**
 * Fastly API class.
 *
 */

namespace Fastly;

use Fastly\Adapter\FastlyRequestAdapter;
use Fastly\Exceptions\FastlyAPIResponseException;
use Fastly\Types\FastlyCertificates;
use Fastly\Types\FastlyPrivateKeys;
use GuzzleHttp\Exception\RequestException;

class Fastly {

  const VERSION = '0.1.0';

  private $entryPoint;
  private $service;
  private $error;

  public $certificates;
  public $private_keys;

  /**
   * Fastly API Client.
   *
   * @param string $token
   * @param string $service
   * @param string $entryPoint
   */
  public function __construct(string $token, $service = '', $entryPoint = 'https://api.fastly.com/') {
    $this->adapter    = new FastlyRequestAdapter($token, $entryPoint);
    $this->service    = $service;
    $this->entryPoint = $entryPoint;

    $this->certificates = new FastlyCertificates($token, $entryPoint);
    $this->private_keys = new FastlyPrivateKeys($token, $entryPoint);
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
    $endpoint = $this->adapter->build_endpoint($uri);

    // Reset errors
    $this->error = null;

    // Send HTTP request.
    try {
      $result = $this->adapter->send($method, $endpoint, $options);
    }
    catch (RequestException $e) {
      //@todo: Add custom exception.
      //$response = $this->RequestErrorHandling($e);
      return $this->error = $e;
    }

    if (!$result) {
      $this->error = $this->adapter->getError();
    }

    return $this->adapter->build_output($result);
  }

  /**
   * Set the service ID.
   *
   * @param string $service
   * @return Fastly
   */
  public function set_service(string $service): Fastly {
    $this->service = $service;
    return $this;
  }

  /**
   * @return mixed
   */
  public function get_error()
  {
    return $this->error;
  }
}