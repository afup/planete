<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsController]
final class RssAction
{
    public function __construct(
        #[Autowire(service: 'planete.php.client')]
        private readonly HttpClientInterface $httpClient,
        private readonly EncoderInterface $encoder,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    #[Route(path: 'rss.php', name: 'rss')]
    public function __invoke(): Response
    {
        $response = $this->httpClient->request('GET', 'articles');

        $data = [
            '@version' => '2.0',
            'channel' => [
                'title' => 'Planète PHP',
                'description' => 'Agrégateur de flux RSS sur le PHP francophone',
                'link' => $this->urlGenerator->generate('rss', referenceType: UrlGeneratorInterface::ABSOLUTE_URL),
                'language' => 'fr-fr',
                'generator' => 'AFUP',
                'managingEditor' => 'planetephpfr@afup.org',
                'item' => array_map(static fn(array $item) => [
                    'title' => $item['title'],
                    'link' => $item['url'],
                    'author' => $item['author'],
                    'date' => $item['date'],
                    'description' => $item['content'],
                ], $response->toArray()),
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
