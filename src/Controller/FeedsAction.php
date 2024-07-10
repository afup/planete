<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\PlaneteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FeedsAction extends AbstractController
{
    public function __construct(
        private readonly PlaneteService $planeteApi,
    ) {}

    #[Route(path: '/feeds', name: 'feeds_list', methods: 'GET')]
    public function __invoke(Request $request): Response
    {
        return $this->render('feeds.html.twig', [
            'feeds' => $this->planeteApi->getFeeds(),
        ]);
    }
}
