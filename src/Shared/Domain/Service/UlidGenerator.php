<?php

namespace App\Shared\Domain\Service;

use Symfony\Component\Uid\Ulid;

class UlidGenerator
{
    public static function generate(): string
    {
        return Ulid::generate();
    }
}