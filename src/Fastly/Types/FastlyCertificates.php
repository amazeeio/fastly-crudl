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

        try {
          $certificate_response = $this->send('GET', $endpoint);
        } catch (RequestException $e) {
          $this->error = $e;
          return $e->getMessage();
        }

        if ($certificate_response !== null || $certificate_response != []) {
            $output = $this->build_output($certificate_response);
            $this->data = $output['data'];

            return $output['data'];
        }
    }

    /**
     * Get Fastly TLS certificates.
     *
     * @param array $filter
     * @return array|mixed
     */
    public function get_tls_certificates($filter = [])
    {
      $filter_encoded_query = http_build_query($filter);
      $endpoint = !empty($filter) ? 'tls/certificates?'.$filter_encoded_query  : 'tls/certificates';

      try {
        $certificates_response = $this->send('GET', $this->build_endpoint($endpoint));
      } catch (RequestException $e) {
        $this->error = $e;
        return $e->getMessage();
      }

      $certificates = [];
      if (!empty($certificates_response)) {
        $output = $this->build_output($certificates_response);
        $this->data = $output['data'];
        $this->meta = $output['meta'];

        foreach ($this->data as $certificate) {
          $certificates['data'][] = new FastlyCertificate($certificate);
        }

        $certificates['meta'][] = $this->meta;
      }
      else {
        return ['data' => "No certificates returned."];
      }

      return $certificates;
    }

    /**
     * Get certificate by id.
     *
     * @param string $id
     * @return mixed
     */
    public function getTLSBulkCertificate($id = '')
    {
        $endpoint = $this->build_endpoint('tls/bulk/certificates/' . $id);

        try {
          $certificate_response = $this->send('GET', $endpoint);
        } catch (RequestException $e) {
          $this->error = $e;
          return $e->getMessage();
        }

        if ($certificate_response !== null && $certificate_response != []) {
          $output = $this->build_output($certificate_response);
          $this->data = $output['data'];

          return new FastlyBulkCertificate($output['data']);
        }
    }

    /**
     * Get Platform TLS bulk certificates.
     *
     * @param string $options
     * @return array|mixed
     */
    public function getTLSBulkCertificates($options = '')
    {
        if ($options === '' || $options === null) {
          try {
            $certificates_response = $this->send('GET', $this->build_endpoint('tls/bulk/certificates'));
          } catch (RequestException $e) {
            $this->error = $e;
            return $e->getMessage();
          }
        }
        else {
          try {
            $certificates_response = $this->send('GET', $this->build_endpoint('tls/bulk/certificates?'.$options));
          } catch (RequestException $e) {
            $this->error = $e;
            return $e->getMessage();
          }
        }

        $certificates = [];

        if ($certificates_response !== null || $certificates_response != []) {
            $output = $this->build_output($certificates_response);
            $this->data = $output['data'];
            $this->links = $output['links'];
            $this->meta = $output['meta'];

            foreach ($this->data as $certificate) {
              $certificates['data'][] = new FastlyBulkCertificate($certificate);
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
     * Send bulk certificates with split public and intermediates certificates.
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
            return new FastlyBulkCertificate($this->build_output($result)['data']);
        }
        return $this->get_error();
    }

  /**
   * Send bulk certificates.
   *
   * @param string $chained_certificate
   * @param string $configurations_id
   *
   * @return array|mixed|string
   */
  public function send_bulk_chained_tls_certificates($chained_certificate, $configurations_id)
  {
    $endpoint = $this->build_endpoint('tls/bulk/certificates');

    $certificates = $this->split_certificates($chained_certificate);

    $options = [
      "data" => [
        "type" => "tls_bulk_certificate",
        "attributes" => [
          "cert_blob" => $certificates['public'],
          "intermediates_blob" => $certificates['chained']
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
      return new FastlyBulkCertificate($this->build_output($result)['data']);
    }
    return $this->get_error();
  }

  /**
   * @param string $chained_certificate
   * @return array
   */
  private function split_certificates(string $chained_certificate)
  {
    list($public, $chained) = preg_split('~(?<=\-----END CERTIFICATE-----)\s~', $chained_certificate,
      NULL,PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

    return [
      'public' => $public,
      'chained' => $chained
    ];
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
     * Replace a TLS certificate with a new TLS certificate.
     *
     * @param $id Certificate ID
     * @param $signed_certificate
     * @param $intermediates_cert
     *
     * @return array|mixed|string
     * @param $certificate
     * @return array|mixed|string
     */
    public function updateTLSBulkCertificate($id, $signedCertificate, $intermediateCertificate)
    {
        $endpoint = $this->build_endpoint('tls/bulk/certificates/' . $id);

        $options = [
            "data" => [
                "id" => $id,
                "type" => "tls_bulk_certificate",
                "attributes" => [
                    "cert_blob" => $signedCertificate,
                    "intermediates_blob" => $intermediateCertificate
                ]
            ]
        ];

        try {
            $result = $this->send('PATCH', $endpoint, $options);
        } catch (RequestException $e) {
            $this->error[] = $e;
            return $e->getMessage();
        }

        if ($result) {
            return new FastlyBulkCertificate($this->build_output($result)['data']);
        }
        return $this->get_error();
    }

    /**
     * Delete a certificate.
     *
     * @param $id
     * @return mixed
     */
    public function delete_tls_certificate($id)
    {
        try {
          $response = $this->send('DELETE', $this->build_endpoint('tls/certificates/') . $id);
        } catch (RequestException $e) {
          $this->error[] = $e;
          return $e->getMessage();
        }

        if ($response) {
          return $response;
        }
        return $this->get_error();
    }

    /**
     * Get certificate by id.
     *
     * @param string $id
     * @return mixed
     */
    public function deleteTLSBulkCertificate($id = '')
    {
        $endpoint = $this->build_endpoint('tls/bulk/certificates/' . $id);

        try {
          $response = $this->send('DELETE', $endpoint);
        } catch (RequestException $e) {
          $this->error[] = $e;
          return $e->getMessage();
        }

        if ($response) {
          return $response;
        }
        return $this->get_error();
    }
}
