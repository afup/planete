<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Article;
use App\Service\PlaneteService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

#[AsController]
final class RssAction
{
    public function __construct(
        private readonly EncoderInterface $encoder,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly PlaneteService $planeteApi,
    ) {}

    #[Route(path: 'rss.php', name: 'rss')]
    public function __invoke(): Response
    {
        $data = [
            '@version' => '2.0',
            'channel' => [
                'title' => 'Planète PHP',
                'description' => 'Agrégateur de flux RSS sur le PHP francophone',
                'link' => $this->urlGenerator->generate('rss', referenceType: UrlGeneratorInterface::ABSOLUTE_URL),
                'language' => 'fr-fr',
                'generator' => 'AFUP',
                'managingEditor' => 'planetephpfr@afup.org',
                'item' => array_map(static fn(Article $article) => [
                    'title' => $article->title,
                    'link' => $article->url,
                    'author' => $article->author,
                    'date' => $article->date->format(DATE_RSS),
                    'description' => $article->content,
                ], $this->planeteApi->getArticles()->articles),
            ],
        ];

        $xml = $this->encoder->encode($data, 'xml', [
            XmlEncoder::ROOT_NODE_NAME => 'rss',
        ]);

        return new Response($xml, headers: [
            'Content-Type' => 'application/xml',
        ]);
    }
}
