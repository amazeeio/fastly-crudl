<?php

namespace Fastly\Tests;

use Dotenv\Dotenv;
use Fastly\Fastly;
use Fastly\Types\FastlyCertificateInterface;
use Fastly\Types\FastlyCertificate;

class FastlyCertificateTest extends \PHPUnit\Framework\TestCase
{

    protected $certificateObject = null;
    protected $fastlyJson = <<<JSON
{
      "id": "TLS_CERTIFICATE_ID",
      "type": "tls_certificate",
      "attributes": {
        "created_at": "2019-02-01T12:12:12.000Z",
        "issued_to": "...",
        "issuer": "Let's Encrypt Authority X3",
        "name": "My certificate",
        "not_after": "2020-02-01T12:12:12.000Z",
        "not_before": "2019-02-01T12:12:12.000Z",
        "replace": false,
        "serial_number": "1234567890",
        "signature_algorithm": "SHA256",
        "updated_at": "2019-02-01T12:12:12.000Z"
        }
}
JSON;
    protected $fastlyObj = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fastlyObj = json_decode($this->fastlyJson, true);
        $this->certificateObject = new FastlyCertificate($this->fastlyObj);
    }

    public function testTopLevelObjectFields()
    {
        $this->assertEquals("TLS_CERTIFICATE_ID", $this->certificateObject->getId());
        $this->assertEquals("tls_certificate", $this->certificateObject->getType());
    }

    public function testFastlyCertificateAttributes()
    {
        $attributeNames = array_keys((array)$this->fastlyObj['attributes']);

        $attributeFieldNames = array_map(function ($element) {
            return "get" . str_replace('_', '', ucwords($element, "_\t\r\n\f\v"));
        }, array_combine($attributeNames, $attributeNames));

        foreach ($attributeFieldNames as $attributeKey => $methodName) {
            $this->assertEquals($this->fastlyObj['attributes'][$attributeKey], $this->certificateObject->$methodName());
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
