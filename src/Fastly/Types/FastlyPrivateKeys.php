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
     * @return array|string
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
            $this->error = $e;
            return $e->getMessage();
        }

        if ($result) {
            return new FastlyPrivateKey($this->build_output($result)['data']);
        } else {
            if ($this->get_error()) {
                $this->error = $this->get_error();
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
    public function get_private_key($id)
    {
        $result = $this->send('GET', $this->build_endpoint('tls/private_keys/') . $id);

        if ($result) {
            return new FastlyPrivateKey($this->build_output($result)['data']);
        }
    }

    /**
     * Get a list of private keys.
     *
     * @return array|mixed
     */
    public function get_private_keys()
    {
        $keys_response = $this->send('GET', $this->build_endpoint('tls/private_keys'));

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
