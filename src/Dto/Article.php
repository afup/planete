<?php

declare(strict_types=1);

namespace App\Dto;

final readonly class Article
{
    public function __construct(
        public string $title,
        public string $url,
        public \DateTimeImmutable $date,
        public ?string $author,
        public string $content,
        public Feed $feed,
    ) {}
}
