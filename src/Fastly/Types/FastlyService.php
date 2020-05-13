<?php

namespace Fastly\Types;

class FastlyService implements FastlyServiceInterface
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

    public function getCustomerId()
    {
        return $this->data['attributes']['customer_id'];
    }

    public function getDeletedAt()
    {
        return $this->data['attributes']['deleted_at'];
    }

    public function getName()
    {
        return $this->data['attributes']['name'];
    }

    public function getUpdatedAt()
    {
        return $this->data['attributes']['updated_at'];
    }

    public function getActiveVersion()
    {
        return $this->data['attributes']['active_version'];
    }
}
