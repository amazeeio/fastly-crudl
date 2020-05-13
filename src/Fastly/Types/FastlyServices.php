<?php

namespace Fastly\Types;

use Fastly\Request\FastlyRequest;
use GuzzleHttp\Exception\RequestException;

class FastlyServices extends FastlyRequest
{
    public $data;
    public $links;
    public $meta;
    protected $domain;
    protected $status;

    /**
     * Get Service by given domain name.
     *
     * @param string $domain
     * @return array|mixed|string
     */
    public function getServiceByDomain($domain = '')
    {
      if ($domain === '' || $domain === null) {
        return $this->data = 'Domain name must be given';
      }
      else {
        $this->domain = $domain;
      }

      $response = $this->send(
        'GET',
        $this->build_endpoint('services?filter[domains.name][match]=' . $domain . '&filter[domains.deleted]=false')
      );

      $output = $this->build_output($response);
      $this->data = $output['data'];
      $this->links = $output['links'];
      $this->meta = $output['meta'];

      return new FastlyService($output['data'][0]);
    }

    public function checkDomainStatusForServiceVersion($service_id, $version, $name = '')
    {
      $domain = $name ? $name : $this->domain;

      $response = $this->send(
        'GET',
        $this->build_endpoint('service/' . $service_id . '/version/' . $version . '/domain/' . $domain . '/check')
      );

      $this->status = $this->build_output($response);

      return $this->status;
    }
}