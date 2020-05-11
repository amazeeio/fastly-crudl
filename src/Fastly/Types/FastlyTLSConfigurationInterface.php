<?php

namespace Fastly\Types;

interface FastlyTLSConfigurationInterface
{
    public function getId();

    public function getType);

    public function getDefault();

    public function getCreatedAt();

    public function getHttpProtocols();

    public function getTLSProtocols();

    public function getName();

    public function getBulk();

    public function getUpdatedAt();

    public function getDNSRecords();
}
