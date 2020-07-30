<?php

namespace Fastly\Types;

use Fastly\Request\FastlyRequest;
use GuzzleHttp\Exception\RequestException;

class FastlyStats extends FastlyRequest
{
    public $data;
    public $links;
    public $meta;

  /**
   * Get stats.
   *
   * @param array $query
   * @param array $filter
   * @return string|array
   */
    public function get_stats($query = [], $filter = [])
    {
      $endpoint = 'stats';
      if (!empty($query)) {
        $endpoint = !empty($query) ? $query : 'stats';
      }

      if (!empty($filter)) {
        $filter_encoded_query = http_build_query($filter);
        $endpoint = !empty($query) ? $endpoint.'?'.$filter_encoded_query  : '?'.$filter_encoded_query;
      }

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
