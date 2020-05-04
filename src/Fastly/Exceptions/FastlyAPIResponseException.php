<?php

namespace Fastly\Exceptions;

class FastlyAPIResponseException extends \Exception {
  protected $details;

  /**
   * FastlyAPIResponseException constructor.
   *
   * @param $details
   */
  public function __construct($details) {
    $this->details = $details;
    parent::__construct();
  }

  public function __toString() {
    return 'Invalid response from Fastly API: ' . $this->details;
  }

}