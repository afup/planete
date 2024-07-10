<?php

declare(strict_types=1);

namespace App\Dto;

final readonly class ArticlePage
{
    /**
     * @param array<Article> $articles
     */
    public function __construct(
        public array $articles,
        public bool $hasNextPage,
    ) {}
}
