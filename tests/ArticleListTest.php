<?php

namespace App\Tests;

use PHPUnit\Framework\Constraint\LogicalAnd;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Test\Constraint\CrawlerSelectorAttributeValueSame;
use Symfony\Component\DomCrawler\Test\Constraint\CrawlerSelectorExists;

class ArticleListTest extends WebTestCase
{
    public function test_root_url_redirects_correctly(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseRedirects('/articles');
    }

    public function test_page_without_articles_redirects_to_page_1(): void
    {
        $client = static::createClient();
        $client->request('GET', '/articles?page=3');

        self::assertResponseRedirects('/articles');
    }

    public function test_display_list_of_articles_on_first_page(): void
    {
        $client = static::createClient();
        $client->request('GET', '/articles');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('[data-qa="site-name"]', 'Planète PHP');

        self::assertSelectorCount(2, '[data-qa="article"]');

        self::assertSelectorTextContains('[data-qa="article-1-title"]', 'Article 1');
        self::assertSelectorTextContains('[data-qa="article-1-date"]', '15/06/2022');
        self::assertSelectorTextContains('[data-qa="article-1-feed"]', 'AFUP');
        self::assertSelectorTextContains('[data-qa="article-1-content"]', "Contenu de l'article 1");

        self::assertSelectorTextContains('[data-qa="article-2-title"]', 'Article 2');
        self::assertSelectorTextContains('[data-qa="article-2-date"]', '11/04/2022');
        self::assertSelectorTextContains('[data-qa="article-2-feed"]', 'Acme');
        self::assertSelectorTextContains('[data-qa="article-2-content"]', "Contenu de l'article 2");

        self::assertSelectorExists('[data-qa="page-next"]');
        self::assertSelectorNotExists('[data-qa="page-prev"]');
    }

    public function test_display_list_of_articles_on_page_2(): void
    {
        $client = static::createClient();
        $client->request('GET', '/articles?page=2');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('[data-qa="site-name"]', 'Planète PHP');

        self::assertSelectorCount(1, '[data-qa="article"]');

        self::assertSelectorTextContains('[data-qa="article-1-title"]', 'Article 3');
        self::assertSelectorTextContains('[data-qa="article-1-date"]', '15/06/2022');
        self::assertSelectorTextContains('[data-qa="article-1-feed"]', 'AFUP');
        self::assertSelectorTextContains('[data-qa="article-1-content"]', "Contenu de l'article 3");

        self::assertSelectorNotExists('[data-qa="page-next"]');
        self::assertSelectorExists('[data-qa="page-prev"]');
    }

    public function test_seo_pagination_root_url(): void
    {
        $client = static::createClient();
        $client->request('GET', '/articles');

        self::assertPageTitleSame('Planète PHP');
        self::assertSelectorNotExists('link[rel="prev"]');
        self::asserAttributeValueSame($client, 'link[rel="canonical"]', 'href', '/articles');
        self::asserAttributeValueSame($client, 'link[rel="next"]', 'href', '/articles?page=2');
    }


    public function test_seo_pagination(): void
    {
        $client = static::createClient();
        $client->request('GET', '/articles?page=2');

        self::assertPageTitleSame('Planète PHP - page 2');
        self::asserAttributeValueSame($client, 'link[rel="prev"]', 'href', '/articles');
        self::asserAttributeValueSame($client, 'link[rel="canonical"]', 'href', '/articles?page=2');
        self::assertSelectorNotExists('link[rel="next"]');
    }

    private static function asserAttributeValueSame(KernelBrowser $client, string $selector, string $attribute, string $expectedValue, string $message = ''): void
    {
        self::assertThat($client->getCrawler(), LogicalAnd::fromConstraints(
            new CrawlerSelectorExists($selector),
            new CrawlerSelectorAttributeValueSame($selector, $attribute, $expectedValue),
        ), $message);
    }
}
