<?php

/**
 * Fastly API class.
 *
 */

namespace Fastly;

use Fastly\Request\FastlyRequest;
use Fastly\Exceptions\FastlyAPIResponseException;
use Fastly\Types\FastlyCertificates;
use Fastly\Types\FastlyPrivateKeys;
use GuzzleHttp\Exception\RequestException;

class Fastly
{

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
    public function __construct(string $token, $service = '', $entryPoint = 'https://api.fastly.com/')
    {
        $this->request    = new FastlyRequest($token, $entryPoint);
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
    public function send($method, $uri, array $options = [])
    {
        $endpoint = $this->request->build_endpoint($uri);

        // Reset errors
        $this->error = null;

        // Send HTTP request.
        try {
            $result = $this->request->send($method, $endpoint, $options);
        } catch (RequestException $e) {
            $this->error = $e;
            return $e->getMessage();
        }

        if (!$result) {
            $this->error = $this->request->get_error();
        }

        return $this->request->build_output($result);
    }

    /**
     * @return mixed
     */
    public function get_error()
    {
        return $this->error;
    }
}
