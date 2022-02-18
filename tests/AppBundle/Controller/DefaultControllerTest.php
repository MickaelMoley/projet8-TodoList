<?php

namespace Tests\AppBundle\Controller;

use http\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testIndex()
    {
        /** @var Client $client */
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

    }
}
