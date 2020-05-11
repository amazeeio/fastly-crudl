<?php

namespace Fastly\Types;

use Fastly\Request\FastlyRequest;
use GuzzleHttp\Exception\RequestException;

class FastlyCertificates extends FastlyRequest
{
    public $data;
    public $links;
    public $meta;

    /**
     * Get certificate by id.
     *
     * @param string $id
     * @return mixed
     */
    public function get_tls_certificate($id = '')
    {
        $endpoint = $this->build_endpoint('tls/certificates/' . $id);
        $certificate_response = $this->send('GET', $endpoint);

        if ($certificate_response !== null || $certificate_response != []) {
            $output = $this->build_output($certificate_response);
            $this->data = $output['data'];

            return $output['data'];
        }
    }

    /**
     * Get certificates.
     *
     * @return array|mixed
     */
    public function get_tls_certificates()
    {
        $certificates_response = $this->send(
            'GET',
            $this->build_endpoint('tls/certificates')
        );

        $certificates = [];

        if ($certificates_response !== null || $certificates_response != []) {
            $output = $this->build_output($certificates_response);
            $this->data = $output['data'];
            $this->links = $output['links'];
            $this->meta = $output['meta'];

            foreach ($this->data as $certificate) {
                $certificates['data'][] = new FastlyCertificate($certificate);
            }

            $certificates['links'][] = $this->links;
            $certificates['meta'][] = $this->meta;
        }
        return $certificates;
    }

    /**
     * Upload a new certificate.
     *
     * @param $signed_certificate
     * @param string $name
     * @return array|mixed|string
     */
    public function send_tls_certificate($signed_certificate, $name = '')
    {
        $endpoint = $this->build_endpoint('tls/certificates');

        $options = [
            "data" => [
                "type" => "tls_certificate",
                "attributes" => [
                    "cert_blob" => $signed_certificate,
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
            return new FastlyCertificate($this->build_output($result)['data']);
        }
        return $this->get_error();
    }

    /**
     * Send bulk certificates.
     *
     * @param $signed_certificate
     * @param $intermediates_cert
     * @param $configurations_id
     *
     * @return array|mixed|string
     */
    public function send_bulk_tls_certificates($signed_certificate, $intermediates_cert, $configurations_id)
    {
        $endpoint = $this->build_endpoint('tls/bulk/certificates');

        $options = [
            "data" => [
                "type" => "tls_bulk_certificate",
                "attributes" => [
                    "cert_blob" => $signed_certificate,
                    "intermediates_blob" => $intermediates_cert
                ],
                "relationships" => [
                    "tls_configurations" => [
                        "data" => [
                            [
                            "type" => "tls_configuration",
                            "id" => $configurations_id
                            ]
                        ]
                    ]
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
            return $this->build_output($result);
        }
        return $this->get_error();
    }

    /**
     * Replace a TLS certificate with a new TLS certificate.
     *
     * @param $id
     * @param $certificate
     * @return array|mixed|string
     */
    public function update_tls_certificate($id, $certificate)
    {
        return self::send_tls_certificate($certificate, $id);
    }

    /**
     * Delete a certificate.
     *
     * @param $id
     * @return array
     */
    public function delete_tls_certificate($id)
    {
        return $this->send('DELETE', $this->build_endpoint('tls/certificates/') . $id);
    }
}
