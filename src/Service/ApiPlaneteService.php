<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Article;
use App\Dto\ArticlePage;
use App\Dto\Feed;
use CuyZ\Valinor\Mapper\TreeMapper;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class ApiPlaneteService implements PlaneteService
{
    public function __construct(
        #[Autowire(service: 'planete.php.client')]
        private HttpClientInterface $httpClient,
        private TreeMapper $mapper,
    ) {}

    public function getArticles(?int $page = null): ArticlePage
    {
        $options = [];

        if ($page !== null) {
            $options['query'] = [
                'page' => $page,
            ];
        }

        $response = $this->httpClient->request('GET', 'articles', $options);

        $rawArticles = $response->toArray();
        $rawHasNextPage = $response->getHeaders()['x-pagination-has-next-page'][0] ?? true;

        $articles = $this->mapper->map(
            'array<' . Article::class . '>',
            $rawArticles,
        );

        return new ArticlePage(
            $articles,
            in_array($rawHasNextPage, ['true', true], true),
        );
    }

    /**
     * @return array<Feed>
     */
    public function getFeeds(): array
    {
        $response = $this->httpClient->request('GET', 'feeds');

        $rawFeeds = $response->toArray();

        return $this->mapper->map(
            'array<' . Feed::class . '>',
            $rawFeeds,
        );
    }
}
