<?php

namespace Fastly\Types;

interface FastlyPrivateKeysInterface
{
    public function getId();

    public function getName();

    public function getType();

    public function getCreatedAt();

    public function getKeyLength();

    public function getKeyType();

    public function getReplace();

    public function getPublicKeyDigest();
}
