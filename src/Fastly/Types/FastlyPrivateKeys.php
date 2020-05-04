<?php

namespace Fastly\Types;

use Fastly\Adapter\FastlyRequestAdapter;
use GuzzleHttp\Exception\RequestException;

class FastlyPrivateKeys extends FastlyRequestAdapter {
  public $data;

  public function __construct($token, $entrypoint) {
    parent::__construct($token, $entrypoint);
  }

  /**
   * Post private key to Fastly.
   *
   * @param $uri
   * @param $private_key
   * @param string $name
   *
   * @return array|string
   */
  public function send_private_key($uri, $private_key, $name = '') {
    $endpoint = $this->build_endpoint($uri);

    $options = [
      "data" => [
        "type" => "tls_private_key",
        "attributes" => [
          "key" => $private_key,
          "name" => $name
        ]
      ]
    ];

    try {
      $result = $this->send('POST', $endpoint, $options);
    }
    catch (RequestException $e) {
      return [$this->error = $e];
    }

    if ($result) {
      return $this->build_output($result);
    }
    else {
      if ($this->getError()) {
        $this->error = $this->getError();
      }
      return $this->error;
    }
  }

  /**
   * Get a TLS private key.
   *
   * @param $id
   * @return array
   */
  public function get_private_key($id) {
    $result = $this->send('GET', $this->build_endpoint('tls/private_keys/') . $id);

    if ($result) {
      return $this->build_output($result);
    }
  }

  public function get_private_keys() {
    $result = $this->send('GET', $this->build_endpoint('tls/private_keys'));

    if ($result) {
      return $this->build_output($result);
    }
  }

  /**
   * Destroy a TLS private key. Only private keys not already matched to any certificates can be deleted.
   *
   * @param $id
   * @return array
   */
  public function delete_private_key($id) {
    return $this->send('DELETE', $this->build_endpoint('tls/private_keys/') . $id);
  }


}