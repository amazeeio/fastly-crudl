<?php

namespace Fastly\Types;

use Fastly\Request\FastlyRequest;
use GuzzleHttp\Exception\RequestException;

class FastlyPrivateKeys extends FastlyRequest
{
    public $data;
    public $meta;

    /**
     * Post private key to Fastly.
     *
     * @param $private_key
     * @param string $name
     *
     * @return FastlyPrivateKey|string
     */
    public function send_private_key($private_key, $name = '')
    {
        $endpoint = $this->build_endpoint('tls/private_keys');

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
        } catch (RequestException $e) {
            $this->error[] = $e;
            return $e->getMessage();
        }

        if ($result) {
            return new FastlyPrivateKey($this->build_output($result)['data']);
        }
        return $this->get_error();
    }

    /**
     * Get a TLS private key.
     *
     * @param $id
     * @return FastlyPrivateKey|string
     */
    public function get_private_key($id)
    {
        try {
          $result = $this->send('GET', $this->build_endpoint('tls/private_keys/') . $id);
        } catch (RequestException $e) {
          $this->error = $e;
          return $e->getMessage();
        }

        if ($result) {
            return new FastlyPrivateKey($this->build_output($result)['data']);
        }

        return $this->get_error();
    }

    /**
     * Get a list of private keys.
     *
     * @param array $filter
     * @return array|mixed
     */
    public function get_private_keys($filter = [])
    {
        $filter_encoded_query = http_build_query($filter);
        $endpoint = !empty($filter) ? 'tls/private_keys?'.$filter_encoded_query  : 'tls/private_keys';

        try {
          $keys_response = $this->send('GET', $this->build_endpoint($endpoint));
        } catch (RequestException $e) {
          $this->error = $e;
          return $e->getMessage();
        }

        $keys = [];

        if ($keys_response !== null || $keys_response != []) {
          $output = $this->build_output($keys_response);
          $this->data = $output['data'];
          $this->meta = $output['meta'];

          foreach ($this->data as $private_key) {
            $keys['data'][] = new FastlyPrivateKey($private_key);
          }

          $keys['meta'][] = $this->meta;
        }
        else {
          return ['data' => "No private keys returned."];
        }

        return $keys;
    }

    /**
     * Destroy a TLS private key. Only private keys not already matched to any certificates can be deleted.
     *
     * @param $id
     * @return array
     */
    public function delete_private_key($id)
    {
        return $this->send('DELETE', $this->build_endpoint('tls/private_keys/') . $id);
    }
}
