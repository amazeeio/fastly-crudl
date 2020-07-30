<?php

namespace Fastly\Types;

use Fastly\Request\FastlyRequest;
use GuzzleHttp\Exception\RequestException;

class FastlyBilling extends FastlyRequest
{
    public $data;
    public $links;
    public $meta;

  /**
   * Get month-to-date billing request.
   *
   * @param string $customer_id
   * @return string|array
   */
    public function get_monthly_billing($customer_id)
    {
      if (empty($customer_id)) {
        return $this->error[] = "No customer_id given";
      }

      $endpoint = 'billing/v2/account_customers/'.$customer_id.'/mtd_invoice';

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
