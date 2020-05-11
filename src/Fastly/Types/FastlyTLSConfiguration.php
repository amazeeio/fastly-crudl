<?php

namespace Fastly\Types;

class FastlyTLSConfiguration implements FastlyTLSConfigurationInterface
{

    protected $data;

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

    public function getDefault()
    {
        return $this->data['attributes']['default'];
    }

    public function getCreatedAt()
    {
        return $this->data['attributes']['created_at'];
    }

    public function getHttpProtocols()
    {
        return $this->data['attributes']['http_protocols'];
    }

    public function getTLSProtocols()
    {
        return $this->data['attributes']['tls_protocols'];
    }

    public function getName()
    {
        return $this->data['attributes']['name'];
    }

    public function getBulk()
    {
        return $this->data['attributes']['bulk'];
    }

    public function getUpdatedAt()
    {
        return $this->data['attributes']['updated_at'];
    }

    public function getDNSRecords()
    {
        return $this->data['relationships']['dns_records']['data'] ?? [];
    }
}
