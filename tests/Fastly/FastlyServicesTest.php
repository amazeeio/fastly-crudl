<?php

namespace Fastly\Tests;

use Dotenv\Dotenv;
use Fastly\Fastly;
use Fastly\Types\FastlyService;

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

    //public function testGetServiceByDomain()
    //{
    //    $servicesObject = $this->fastly->services;
    //    $certificates = $this->fastly->certificates->getTLSBulkCertificates();
    //
    //    foreach ($certificates['data'] as $key => $certificate) {
    //      $domains = $certificate->getTLSDomains();
    //
    //      foreach ($domains as $domain) {
    //        $service = $servicesObject->getServiceByDomain($domain['id']);
    //        if ($service instanceof FastlyService) {
    //          var_dump($service->getId());
    //          var_dump($service->getActiveVersion());
    //          var_dump($domain['id']);
    //        }
    //      }
    //    }
    //}

    //public function testCheckDomainStatusForServiceVersion()
    //{
    //  $servicesObject = $this->fastly->services;
    //
    //  $status = $servicesObject->checkDomainStatusForServiceVersion(
    //    "3udRDWOM5w1EKPC2hQqg88",
    //    "11",
    //    "nginx.develop.uu-myaccount-portal.quu-test.amazee.io"
    //  );
    //}
}
