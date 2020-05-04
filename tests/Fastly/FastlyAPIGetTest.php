<?php

namespace Fastly\Tests;

use Dotenv\Dotenv;
use Fastly\Fastly;

class FastlyAPIGetTest extends \PHPUnit\Framework\TestCase {

  private $fastly;
  private $fastly_service_id;

  protected function setUp(): void {
    $dotenv = Dotenv::createImmutable('./');
    $dotenv->load();

    $fastly_api_key = getenv('FASTLY_API_KEY');
    $this->fastly_service_id = getenv('FASTLY_SERVICE_ID');

    $this->fastly = new Fastly($fastly_api_key, $this->fastly_service_id);
  }

  public function testGetStatsResponse() {
    $response = $this->fastly->send('GET', 'stats?from=1+day+ago');

    $this->assertEquals('success', $response['status']);
    $this->assertArrayHasKey('data', $response);
    $this->assertArrayHasKey('msg', $response);
    $this->assertArrayHasKey('meta', $response);
  }

//  public function testGetDomainResponse() {
//    $response = $this->fastly->send('GET', 'service/'. $this->fastly_service_id .'/version/1/domain/check_all');
//    $this->assertArrayHasKey('name', $response[0][0]);
//    $this->assertArrayHasKey('service_id', $response[0][0]);
//    $this->assertArrayHasKey('created_at', $response[0][0]);
//  }

  protected function tearDown(): void {

  }

}