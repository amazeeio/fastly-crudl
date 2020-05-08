<?php

namespace Fastly\Types;

class FastlyPrivateKey implements FastlyPrivateKeysInterface
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

    public function getName()
    {
        return $this->data['attributes']['name'];
    }

    public function getKeyLength()
    {
        return $this->data['attributes']['key_length'];
    }

    public function getKeyType()
    {
        return $this->data['attributes']['key_type'];
    }

    public function getPublicKeyDigest()
    {
        return $this->data['attributes']['public_key_sha1'];
    }

    public function getReplace()
    {
        return $this->data['attributes']['replace'];
    }
}
