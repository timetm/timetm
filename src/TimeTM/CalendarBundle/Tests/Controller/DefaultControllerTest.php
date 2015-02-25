<?php

namespace TimeTM\CalendarBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase {

  public function testIndexRedirect() {

    $client = static::createClient();

//     $client->followRedirects();
    
    $crawler = $client->request('GET', '/login');
    
    $form = $crawler->selectButton('Connexion')->form();
    
    $client->submit($form, array('_username' => 'admin', '_password' => '1234'));
    
    $this->assertTrue($client->getResponse()->isRedirect());
  }

//   public function testIndexRedirectUrl() {
  
//   	$client = static::createClient();
  
//   	$client->request('GET', '/');
  	
//   	$client->followRedirect();
  	
//   	$this->assertRegExp('/login/', $client->getHistory()->current()->getUri());
//   }


//   public function testMonth() {
  
//     $client = static::createClient();
  
//     $crawler = $client->request('GET', '/month');
  
//     $this->assertCount(1, $crawler->filter('td:contains("Mois")'));
//   }
  
  
}
// $crawler = $client->followRedirect();
// $client->followRedirects();