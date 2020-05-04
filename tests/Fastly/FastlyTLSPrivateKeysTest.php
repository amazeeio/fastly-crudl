<?php

namespace Fastly\Tests;

use Dotenv\Dotenv;
use Fastly\Fastly;

class FastlyTLSPrivateKeysTest extends \PHPUnit\Framework\TestCase {

  private $fastly;
  private $fastly_service_id;

  protected function setUp(): void {
    $dotenv = Dotenv::createImmutable('./');
    $dotenv->load();

    $fastly_api_token = getenv('FASTLY_API_KEY');
    $this->fastly_service_id = getenv('FASTLY_SERVICE_ID');

    $this->fastly = new Fastly($fastly_api_token, $this->fastly_service_id);

    // Generate a new private (and public) key pair
    $privkey = openssl_pkey_new(array(
      "private_key_bits" => 2048,
      "private_key_type" => OPENSSL_KEYTYPE_RSA,
    ));

    openssl_pkey_export($privkey, $pkeyout);
    $this->private_key = $pkeyout;
  }

  public function testGetTLSPrivateKeys()
  {
    $keys = $this->fastly->private_keys;
    $response = $keys->get_private_keys();

    $this->assertArrayHasKey('data', $response);
  }

  public function testGetSpecificTLSPrivateKey()
  {
    $id = '2RZmS0uEBI4mnyXI0ztqx0';
    $keys = $this->fastly->private_keys;

    $get_key = $keys->get_private_key($id);
    $this->assertArrayHasKey('data', $get_key);
  }

//  public function testUploadPrivateKeys()
//  {
//    $keys = $this->fastly->private_keys;
//    $response = $keys->send_private_key($this->private_key, $name = '');
//
//    $this->assertEquals('tls_private_key', $response['data']['type']);
//    $this->assertArrayHasKey('id', $response['data']);
//    $this->assertArrayHasKey('attributes', $response['data']);
//  }

//  public function testDeletePrivateKeys()
//  {
//    $id = "6bWQlIGscXMA86GChdi7q9";
//    $keys = $this->fastly->private_keys;
//    $response = $keys->delete_private_key($id);
//
//    $this->assertStringContainsString('No Content', $response[0]);
//  }

}