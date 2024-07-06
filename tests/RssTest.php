<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RssTest extends WebTestCase
{
    public function test_rss_feed_is_correctly_generated(): void
    {
        $client = static::createClient();
        $client->request('GET', '/rss.php');

        self::assertResponseIsSuccessful();

        $response = $client->getResponse()->getContent();

        self::assertIsString($response);
        self::assertXmlStringEqualsXmlFile('tests/fixtures/rss.xml', $response);
    }
}
