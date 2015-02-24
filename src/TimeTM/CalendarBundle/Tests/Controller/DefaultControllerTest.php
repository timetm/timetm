<?php

namespace TimeTM\CalendarBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase {

  public function testIndex() {

    $client = static::createClient();
  
    $crawler = $client->request('GET', '/');
  
    $this->assertTrue($crawler->filter('html:contains("index")')->count() == 1);
  }

  public function testMonth() {
  
    $client = static::createClient();
  
    $crawler = $client->request('GET', '/month');
  
    $this->assertCount(1, $crawler->filter('td:contains("Mois")'));
  }
  
  
}
