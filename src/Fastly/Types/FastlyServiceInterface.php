<?php

namespace Fastly\Types;

interface FastlyServiceInterface
{
    public function getId();
    public function getType();
    public function getCreatedAt();
    public function getCustomerId();
    public function getDeletedAt();
    public function getName();
    public function getUpdatedAt();
    public function getActiveVersion();
}
