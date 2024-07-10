<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ArticlePage;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final readonly class CachedPlaneteService implements PlaneteService
{
    public function __construct(
        private PlaneteService $inner,
        private CacheInterface $cache,
    ) {}

    public function getArticles(?int $page = null): ArticlePage
    {
        return $this->cache->get('planete_articles', function (ItemInterface $item) use ($page): ArticlePage {
            $item->expiresAfter(3600);

            return $this->inner->getArticles($page);
        });
    }

    public function getFeeds(): array
    {
        return $this->cache->get('planete_articles', function (ItemInterface $item): array {
            $item->expiresAfter(3600);

            return $this->inner->getFeeds();
        });
    }
}
