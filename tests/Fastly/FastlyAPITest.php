<?php

namespace Fastly\Tests;

use Dotenv\Dotenv;
use Fastly\Fastly;

class FastlyAPITest extends \PHPUnit\Framework\TestCase {

  private $fastly;
  private $adapter;
  private $fastly_service_id;

  protected function setUp(): void {
    $dotenv = Dotenv::createImmutable('./');
    $dotenv->load();

    $fastly_api_key = getenv('FASTLY_API_KEY');
    $this->fastly_service_id  = getenv('FASTLY_SERVICE_ID');

    $this->adapter = new \Fastly\Adapter\FastlyAdapter($fastly_api_key);
    $this->fastly = new Fastly($this->adapter, $this->fastly_service_id);
  }

  public function testFalseAPIKeyResponse() {
    $fake_adapter = new \Fastly\Adapter\FastlyAdapter('this_is_definitely_not_a_valid_key');
    $fastly = new Fastly($fake_adapter, $this->fastly_service_id);

    $response = $fastly->send('GET', 'stats?from=1+day+ago');

    $this->assertStringContainsString('403 Forbidden', $response);
  }

  public function testGetStatsResponse() {
    $response = $this->fastly->send('GET', 'stats?from=1+day+ago');

    $this->assertEquals('success', $response['status']);
    $this->assertArrayHasKey('data', $response);
    $this->assertArrayHasKey('msg', $response);
    $this->assertArrayHasKey('meta', $response);
  }

  public function testGetTLSKeysResponse() {
    $response = $this->fastly->send('GET', 'tls/private_keys');

    $this->assertArrayHasKey('data', $response);
  }

  public function testGetDomainResponse() {
    $response = $this->fastly->send('GET', 'service/'. $this->fastly_service_id .'/version/1/domain/check_all');

    $this->assertArrayHasKey('name', $response[0][0]);
    $this->assertArrayHasKey('service_id', $response[0][0]);
    $this->assertArrayHasKey('created_at', $response[0][0]);
  }

  protected function tearDown(): void {

  }

}