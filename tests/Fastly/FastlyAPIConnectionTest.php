<?php

namespace Fastly\Tests;

use Dotenv\Dotenv;
use Fastly\Fastly;

class FastlyAPIConnectionTest extends \PHPUnit\Framework\TestCase {

  private $fastly;
  private $fastly_service_id;

  protected function setUp(): void {
    $dotenv = Dotenv::createImmutable('./');
    $dotenv->load();

    $fastly_api_key = getenv('FASTLY_API_KEY');
    $this->fastly_service_id  = getenv('FASTLY_SERVICE_ID');
    $this->fastly = new Fastly($fastly_api_key, $this->fastly_service_id);
  }

  public function testFalseAPIKeyResponse() {
    $fastly = new Fastly('this_is_definitely_not_a_valid_key', $this->fastly_service_id);

    $fastly->send('GET', 'stats?from=1+day+ago');

    $this->assertStringContainsString('403 Forbidden', $fastly->get_error());
  }

  public function testAPIConnection() {
    $response = $this->fastly->send('GET', 'stats?from=1+day+ago');

    $this->assertStringContainsString('success', $response['status']);
  }

  protected function tearDown(): void {

  }

}