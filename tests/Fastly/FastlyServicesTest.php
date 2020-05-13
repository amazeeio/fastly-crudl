<?php

namespace Fastly\Tests;

use Dotenv\Dotenv;
use Fastly\Fastly;

class FastlyServicesTest extends \PHPUnit\Framework\TestCase
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

    public function testGetServiceByDomain()
    {
        $servicesObject = $this->fastly->services;

        $service = $servicesObject->getServiceByDomain("nginx.develop.uu-myaccount-portal.quu-test.amazee.io");

        // Get whole response from API.
        $this->assertObjectHasAttribute('data', $service);
    }
}
