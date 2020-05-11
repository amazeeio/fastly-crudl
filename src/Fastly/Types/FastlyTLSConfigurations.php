<?php

namespace Fastly\Types;

use Fastly\Request\FastlyRequest;
use GuzzleHttp\Exception\RequestException;

class FastlyTLSConfigurations extends FastlyRequest
{
    protected $data;
    protected $meta;

    /**
     * Get a TLS Configuration key.
     *
     * @param $id
     * @return array
     */
    public function getConfiguration($id)
    {
        $result = $this->send('GET', $this->build_endpoint('tls/configurations/') . $id);

        if ($result) {
            return new FastlyTLSConfiguration($this->build_output($result)['data']);
        }
    }

    /**
     * Get a list of TLS configurations.
     *
     * @return array|mixed
     */
    public function getConfigurations()
    {
        $response = $this->send('GET', $this->build_endpoint('tls/configurations'));

        $configurations = [];

        if ($response !== null || $response != []) {
            $output = $this->build_output($response);
            $this->data = $output['data'];
            $this->meta = $output['meta'];

            foreach ($this->data as $configuration) {
                $configurations['data'][] = new FastlyTLSConfiguration($configuration);
            }

            $keys['meta'][] = $this->meta;
        }

        return $configurations;
    }
}
