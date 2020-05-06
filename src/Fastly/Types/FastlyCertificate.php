<?php

namespace Fastly\Types;


class FastlyCertificate implements FastlyCertificateInterface
{

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getId()
    {
        return $this->data['id'];
    }

    public function getType()
    {
        return $this->data['type'];
    }

    public function getCreatedAt()
    {
        return $this->data['attributes']['created_at'];
    }

    public function getIssuedTo()
    {
        return $this->data['attributes']['issued_to'];
    }

    public function getIssuer()
    {
        return $this->data['attributes']['issuer'];
    }

    public function getName()
    {
        return $this->data['attributes']['name'];
    }

    public function getNotAfter()
    {
        return $this->data['attributes']['not_after'];
    }

    public function getNotBefore()
    {
        return $this->data['attributes']['not_before'];
    }

    public function getReplace()
    {
        return $this->data['attributes']['replace'];
    }

    public function getSerialNumber()
    {
        return $this->data['attributes']['serial_number'];
    }

    public function getSignatureAlgorithm()
    {
        return $this->data['attributes']['signature_algorithm'];
    }

    public function getUpdatedAt()
    {
        return $this->data['attributes']['updated_at'];
    }

    public function getTlsDomains()
    {
        return $this->data['relationships']['tls_domains']['data'];
    }
}
