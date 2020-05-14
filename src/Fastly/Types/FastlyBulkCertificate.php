<?php

namespace Fastly\Types;

class FastlyBulkCertificate implements FastlyBulkCertificateInterface
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

    public function getCreatedAt()
    {
        return $this->data['attributes']['created_at'];
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

    public function getUpdatedAt()
    {
        return $this->data['attributes']['updated_at'];
    }

    public function getTlsDomains()
    {
        return $this->data['relationships']['tls_domains']['data'];
    }

    public function getConfigurations()
    {
        return $this->data['relationships']['tls_configurations']['data'];
    }

    public function getService()
    {
        return $this->data !== null ? $this->data['service'] : null;
    }

    public function setService($service)
    {
      return $this->data['service'] = $service;
    }

    public function getStatus()
    {
      return $this->data !== null ? $this->data['status'] : null;
    }

    public function setStatus($status)
    {
      return $this->data['status'] = $status;
    }
}
