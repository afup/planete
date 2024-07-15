<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\PlaneteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticlesAction extends AbstractController
{
    public function __construct(
        private readonly PlaneteService $planeteApi,
    ) {}

    #[Route(path: '/articles', name: 'articles_list', methods: 'GET')]
    public function __invoke(Request $request): Response
    {
        // Redirection de /articles?page=1 vers /articles
        if ($request->query->has('page') && $request->query->getInt('page') === 1) {
            return $this->redirectToRoute('articles_list', status: Response::HTTP_MOVED_PERMANENTLY);
        }
        $page = $request->query->getInt('page', 1);

        $articlesPage = $this->planeteApi->getArticles($page);

        if (count($articlesPage->articles) === 0 && $page !== 1) {
            return $this->redirectToRoute('articles_list');
        }

        return $this->render('articles.html.twig', [
            'articles' => $articlesPage->articles,
            'page' => $page,
            'hasNextPage' => $articlesPage->hasNextPage,
        ]);
    }
}
