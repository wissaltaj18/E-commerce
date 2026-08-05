<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testProductsPageLoads(): void
    {
        $client = static::createClient();

        $client->request('GET', '/products');

        $this->assertResponseIsSuccessful();
    }
}