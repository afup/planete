<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Component\HttpClient\Response\JsonMockResponse;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

final readonly class MockClientCallback
{
    public function __construct(
        private string $paramPlanetePhpApiPrefix,
    ) {}

    /**
     * @param array<mixed> $options
     */
    public function __invoke(string $method, string $url, array $options = []): ResponseInterface
    {
        if ($url === $this->paramPlanetePhpApiPrefix . 'articles?page=1'
            || $url === $this->paramPlanetePhpApiPrefix . 'articles'
        ) {
            return new JsonMockResponse(
                [
                    [
                        'title' => 'Article 1',
                        'url' => 'https://example.com/article-1',
                        'date' => 'Wed, 15 Jun 2022 02:34:44 +0200',
                        'author' => 'Association AFUP',
                        'content' => "Contenu de l'article 1",
                        'feed' => [
                            'name' => 'AFUP',
                            'url' => 'https://afup.org',
                        ],
                    ],
                    [
                        'title' => 'Article 2',
                        'url' => 'https://example.com/article-2',
                        'date' => 'Mon, 11 Apr 2022 07:33:41 +0200',
                        'author' => 'Acme Corp',
                        'content' => "Contenu de l'article 2",
                        'feed' => [
                            'name' => 'Acme',
                            'url' => 'https://example.com',
                        ],
                    ],
                ],
                [
                    'response_headers' => [
                        'X-Pagination-Total' => 3,
                        'X-Pagination-Per-Page' => 20,
                        'X-Pagination-Has-Next-Page' => true,
                    ],
                ],
            );
        }

        if ($url === $this->paramPlanetePhpApiPrefix . 'articles?page=2') {
            return new JsonMockResponse(
                [
                    [
                        'title' => 'Article 3',
                        'url' => 'https://example.com/article-3',
                        'date' => 'Wed, 15 Jun 2022 02:34:44 +0200',
                        'author' => 'Association AFUP',
                        'content' => "Contenu de l'article 3",
                        'feed' => [
                            'name' => 'AFUP',
                            'url' => 'https://afup.org',
                        ],
                    ],
                ],
                [
                    'response_headers' => [
                        'X-Pagination-Total' => 3,
                        'X-Pagination-Per-Page' => 2,
                        'X-Pagination-Has-Next-Page' => false,
                    ],
                ],
            );
        }

        if ($url === $this->paramPlanetePhpApiPrefix . 'articles?page=3') {
            return new JsonMockResponse(
                [],
                [
                    'response_headers' => [
                        'X-Pagination-Total' => 3,
                        'X-Pagination-Per-Page' => 2,
                        'X-Pagination-Has-Next-Page' => false,
                    ],
                ],
            );
        }

        if ($url === $this->paramPlanetePhpApiPrefix . 'feeds') {
            return new JsonMockResponse(
                [
                    [
                        'name' => 'AFUP',
                        'url' => 'https://afup.org',
                    ],
                    [
                        'name' => 'Acme',
                        'url' => 'https://example.com',
                    ],
                ],
            );
        }

        return new MockResponse('', ['http_code' => 404]);
    }
}
