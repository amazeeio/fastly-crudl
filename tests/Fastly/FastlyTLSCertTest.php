<?php

namespace Fastly\Tests;

use Dotenv\Dotenv;
use Fastly\Fastly;

class FastlyTLSCertTest extends \PHPUnit\Framework\TestCase
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

        $dn = array(
            "countryName" => "GB",
            "commonName" => "example.com",
        );

        // Generate a new private (and public) key pair
        $privkey = openssl_pkey_new(array(
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ));

        // Generate a certificate signing request
        $csr = openssl_csr_new($dn, $privkey);

        // Generate a self-signed cert, valid for 365 days
        $x509 = openssl_csr_sign($csr, null, $privkey, $days = 365);

        // Save your private key, CSR and self-signed cert for later use
        openssl_csr_export($csr, $csrout);
        openssl_x509_export($x509, $certout);
        openssl_pkey_export($privkey, $pkeyout);

        $this->private_key = $pkeyout;
        $this->cert_sign_request_out = $csrout;
        $this->signed_cert_out = $certout;
    }

    public function testGetCertificates()
    {
        $certificatesObject = $this->fastly->certificates;
        $certificates = $certificatesObject->get_tls_certificates();

        // Get whole response from API.
        $this->assertArrayHasKey('data', $certificates);
        $this->assertArrayHasKey('links', $certificates);
        $this->assertArrayHasKey('meta', $certificates);
    }

    public function testGetCertificateByID()
    {
        $certificatesObject = $this->fastly->certificates;
        $certificate = $certificatesObject->get_tls_certificate("1JP0gerEJXIxImRnRLckug");

        $this->assertArrayHasKey('id', $certificate);
    }

    //  public function testUploadCertificate()
    //  {
    //$cert = <<<EOD
//-----BEGIN CERTIFICATE-----
//MIIC0DCCAbgCCQDc6eXMYGSx3TANBgkqhkiG9w0BAQsFADAqMSgwJgYDVQQDDB9i
//cmF2ZS1sYW1hcnItY2FhM2U1Lm5ldGxpZnkuYXBwMB4XDTIwMDQzMDIwMjEwN1oX
//DTIxMDQzMDIwMjEwN1owKjEoMCYGA1UEAwwfYnJhdmUtbGFtYXJyLWNhYTNlNS5u
//ZXRsaWZ5LmFwcDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAMHyCdxf
//ENTTinhqN5oGqxKW9obYle4zVQs2IERpM+o86d1kRgscR7B4EwvFhnM5M1wj1Po4
//gif5+KHfAGMgah7psErAuJxCJtlZAc/kfItsB5A4+7u9BGIqx4+kVudxMc4BoNuT
//gr4NCcuFH8bSwzAmlJ1D7k5meIpUInU7h8XPRslVc4kl/ZJDKjWFP0MldUOUm18P
//3S1BbD8Y3O4zzyxBtLzTB4qDZ1v21Wfg6TWH2Y2gI234yGK6b3Iqthlfx4RDni6j
//IdtISqrbgWRCskdi4Up7osr3HkPNlSljX6z05qQyGq7H5x4d+TwPuntpMuCtT4Rc
//YyBNS0CFPpLUgG0CAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAuVua9uM7w1BKUcuO
//hsEVm7ltLF/CxVRXfv++/Nl/wvxGkUbNn5qsMibvx1CzDP6ejqHhhSadMfnaiBpN
//3NMYi5Mu4RlRwQsvnD6DVjmnL9WtgFRXUwrIA+eSeFqd317uQOOZkwwsmY/KDAi0
//HlzIkMgMlg6CaHx7txIQk9I2RtX1aBM+c9+d1eqmOt8gK3bQbW5FpJteebNyDoTR
//VfBsSq7aabLMMSOrTuIXFbsinXMaKUAzvTUZ4rs+z2WOrhBKTZfrfaXBDaxgLsLU
//S1pPoepk0EfA+f06/ZVMeIq5GWd8sM3Xfj+8FzJsqPEqTX9PNcotKnx9fTpAJwGB
//ssJ2UA==
//-----END CERTIFICATE-----
//EOD;
//
//    $response = $this->fastly->send_tls_certificate($cert);
//    var_dump($response);
//  }
}
