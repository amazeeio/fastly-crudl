<?php

namespace Fastly\Tests;

use Dotenv\Dotenv;
use Fastly\Fastly;

class FastlyTLSBulkCertificateTest extends \PHPUnit\Framework\TestCase
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

        //$this->private_key = file_get_contents('tests/Fastly/Fixtures/key.pem');
        //$this->public_chained_cert = file_get_contents('tests/Fastly/Fixtures/public_and_chained_certificate.pem');
        //$this->configurations_id = getenv('FASTLY_CONFIG_ID');
    }

    public function testGetCertificates()
    {
        $certificatesObject = $this->fastly->certificates;
        $certificates = $certificatesObject->getTLSBulkCertificates();

        // Get whole response from API.
        $this->assertArrayHasKey('data', $certificates);
        $this->assertArrayHasKey('links', $certificates);
        $this->assertArrayHasKey('meta', $certificates);
    }

    //public function testGetCertificateByID()
    //{
    //    $certificatesObject = $this->fastly->certificates;
    //    $certificate = $certificatesObject->get_tls_certificate("1JP0gerEJXIxImRnRLckug");
    //
    //    $this->assertArrayHasKey('id', $certificate);
    //}

    //public function testSendBulkCertificates()
    //{
    //  $certificatesObject = $this->fastly->certificates;
    //
    //  $response = $certificatesObject->send_bulk_chained_tls_certificates(
    //    $this->public_chained_cert,
    //    $this->configurations_id
    //  );
    //
    //  $this->assertObjectHasAttribute('data', $response);
    //}

    //public function testUpdateBulkCertificates()
    //{
    //  $certificatesObject = $this->fastly->certificates;
    //
    //  $response = $certificatesObject->updateTLSBulkCertificate(
    //    "13KPV8LASGXes0c9vSjrLb",
    //    $this->public_chained_cert,
    //    $this->configurations_id
    //  );
    //}

    //public function testGetServiceByDomain()
    //{
    //  $certificatesObject = $this->fastly->certificates;
    //  $response = $certificatesObject->getServiceByDomain("nginx.develop.uu-myaccount-portal.quu-test.amazee.io");
    //}
}
