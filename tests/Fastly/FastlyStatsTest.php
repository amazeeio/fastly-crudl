<?php

namespace Fastly\Tests;

use Dotenv\Dotenv;
use Fastly\Fastly;

class FastlyStatsTest extends \PHPUnit\Framework\TestCase
{

    private $fastly;
    private $fastly_service_id;

    protected function setUp(): void
    {
        $dotenv = Dotenv::createImmutable('./');
        $dotenv->load();

        $fastly_api_token = getenv('FASTLY_API_KEY');
        $this->fastly_service_id = getenv('FASTLY_SERVICE_ID');

        $this->fastly = new Fastly($fastly_api_token, $this->fastly_service_id);
    }

    public function testGetStats()
    {
        $stats = $this->fastly->stats->get_stats();

        $this->assertArrayHasKey('data', $stats);
    }

    public function testGetStatsWithQuery()
    {
      $query = "stats/usage_by_month";
      $stats = $this->fastly->stats->get_stats($query);

      $this->assertArrayHasKey('data', $stats);
    }

    public function testGetStatsWithFilter()
    {
      $filter = [
        "month" => "3"
      ];
      $stats = $this->fastly->stats->get_stats('stats', $filter);

      $this->assertArrayHasKey('data', $stats);
    }

    public function testGetStatsWithQueryAndFilter()
    {
      $filter = [
        "month" => "3"
      ];
      $stats = $this->fastly->stats->get_stats('stats/usage_by_month', $filter);

      $this->assertArrayHasKey('data', $stats);
    }
}
