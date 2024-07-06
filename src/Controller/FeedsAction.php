<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class FeedsAction extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'planete.php.client')]
        private readonly HttpClientInterface $httpClient,
    ) {}

    #[Route(path: '/feeds', name: 'feeds_list', methods: 'GET')]
    public function __invoke(Request $request): Response
    {
        $response = $this->httpClient->request('GET', 'feeds');

        return $this->render('feeds.html.twig', [
            'feeds' => $response->toArray(),
        ]);
    }
}
