<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ArticlePage;
use App\Dto\Feed;

interface PlaneteService
{
    public function getArticles(?int $page = null): ArticlePage;

    /**
     * @return array<Feed>
     */
    public function getFeeds(): array;
}
