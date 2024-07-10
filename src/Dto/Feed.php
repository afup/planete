<?php

declare(strict_types=1);

namespace App\Dto;

final readonly class Feed
{
    public function __construct(
        public string $name,
        public string $url,
    ) {}
}
