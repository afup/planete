<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FeedsTest extends WebTestCase
{
    public function test_display_list_of_feeds(): void
    {
        $client = static::createClient();
        $client->request('GET', '/feeds');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('[data-qa="site-name"]', 'Planète PHP');

        self::assertSelectorTextContains('[data-qa="feed-1-name"]', 'AFUP');
        self::assertSelectorTextContains('[data-qa="feed-1-url-desk"]', 'https://afup.org');
        self::assertSelectorTextContains('[data-qa="feed-1-url-mobile"]', 'https://afup.org');

        self::assertSelectorTextContains('[data-qa="feed-2-name"]', 'Acme');
        self::assertSelectorTextContains('[data-qa="feed-2-url-desk"]', 'https://example.com');
        self::assertSelectorTextContains('[data-qa="feed-2-url-mobile"]', 'https://example.com');
    }
}
