<?php

namespace Fastly\Types;

use Fastly\Request\FastlyRequest;
use GuzzleHttp\Exception\RequestException;

class FastlyDomains extends FastlyRequest
{
    public $data;
    public $links;
    public $meta;

  /**
   * Get domains.
   *
   * @param array $filter
   * @return string|array
   */
    public function get_domains($filter = [])
    {
      $filter_encoded_query = http_build_query($filter);
      $endpoint = !empty($filter) ? 'tls/domains?'.$filter_encoded_query  : 'tls/domains';

      try {
        $response = $this->send(
          'GET',
          $this->build_endpoint($endpoint)
        );
      }
      catch (RequestException $e) {
        $this->error[] = $e;
        return $e->getMessage();
      }

      if ($response) {
        return $this->data = $this->build_output($response);
      }
      return $this->get_error();
    }


}
