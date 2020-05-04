<?php

namespace Fastly\Types;

use Fastly\Adapter\FastlyRequestAdapter;
use GuzzleHttp\Exception\RequestException;

class FastlyCertificates extends FastlyRequestAdapter {
  public $data;
  public $links;
  public $meta;

  public function __construct($token, $entrypoint) {
    parent::__construct($token, $entrypoint);
  }

  public function get_tls_certificate($id = '') {
    $endpoint = $this->build_endpoint('tls/certificates/' . $id);
    $certificate_response = $this->send('GET', $endpoint);

    if ($certificate_response !== null || $certificate_response != []) {
      $output = $this->build_output($certificate_response);
      $this->data = $output['data'];

      return $output['data'];
    }
  }

  /**
   *
   */
  public function get_tls_certificates() {

    $certificates_response = $this->send('GET', $this->build_endpoint('tls/certificates'));

    if ($certificates_response !== null || $certificates_response != []) {
      $output = $this->build_output($certificates_response);
      $this->data = $output['data'];
      $this->links = $output['links'];
      $this->meta = $output['meta'];

      return $output;
    }
    else {
      return [];
    }
  }

  /**
   * Upload a new certificate.
   *
   * @param $signed_certificate
   * @param string $name
   * @return array|mixed|string
   */
  public function send_tls_certificate($signed_certificate, $name = '') {
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
    }
    catch (RequestException $e) {
      return [$this->error = $e];
    }

    if ($result) {
      return $this->build_output($result);
    }
    else {
      if (parent::getError()) {
        $this->error = parent::getError();
      }
      return $this->error;
    }
  }

  /**
   * Replace a TLS certificate with a new TLS certificate.
   *
   * @param $id
   * @param $certificate
   * @return array|mixed|string
   */
  public function update_tls_certificate($id, $certificate) {
    return self::send_tls_certificate($certificate, $id);
  }

  /**
   * Delete a certificate.
   *
   * @param $id
   * @return array
   */
  public function delete_tls_certificate($id) {
    return $this->send('DELETE', $this->build_endpoint('tls/certificates/') . $id);
  }
}