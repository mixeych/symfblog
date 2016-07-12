<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    public function testCreateArticle()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/blog/create');
        $form = $crawler->selectButton('submit')->form();
        $form['title'] = 'testing';
        $form['content'] = 'testing... content...';
        $crawler = $client->submit($form);
    }
}

