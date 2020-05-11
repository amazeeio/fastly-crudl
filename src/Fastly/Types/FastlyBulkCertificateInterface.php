<?php

namespace Fastly\Types;

interface FastlyBulkCertificateInterface
{
    public function getId();
    public function getType();
    public function getCreatedAt();
    public function getNotAfter();
    public function getNotBefore();
    public function getReplace();
    public function getUpdatedAt();
    public function getTlsDomains();
    public function getConfigurations();
}
