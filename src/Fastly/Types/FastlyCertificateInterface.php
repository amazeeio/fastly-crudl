<?php

namespace Fastly\Types;

interface FastlyCertificateInterface
{

    public function getId();

    public function getType();

    public function getCreatedAt();

    public function getIssuedTo();

    public function getIssuer();

    public function getName();

    public function getNotAfter();

    public function getNotBefore();

    public function getReplace();

    public function getSerialNumber();

    public function getSignatureAlgorithm();

    public function getUpdatedAt();
}