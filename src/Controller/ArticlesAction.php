<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ArticlesAction extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'planete.php.client')]
        private readonly HttpClientInterface $httpClient,
    ) {}

    #[Route(path: '/articles', name: 'articles_list', methods: 'GET')]
    public function __invoke(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        $response = $this->httpClient->request('GET', 'articles', [
            'query' => [
                'page' => $page,
            ],
        ]);

        $articles = $response->toArray();

        if (count($articles) === 0 && $page !== 1) {
            return $this->redirectToRoute('articles_list');
        }

        $hasNextPage = $response->getHeaders()['x-pagination-has-next-page'][0] ?? true;

        return $this->render('articles.html.twig', [
            'articles' => $articles,
            'page' => $page,
            'hasNextPage' => $hasNextPage,
        ]);
    }
}
