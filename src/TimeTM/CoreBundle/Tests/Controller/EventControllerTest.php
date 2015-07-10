<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventControllerTest extends WebTestCase
{

	public function setUp()
	{
		$this->client = static::createClient(array(), array(
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => '1234',
		));
	}

    public function testIndex()
    {
    	print " testing event index with a direct get ... ";

        $crawler = $this->client->request('GET', '/event/');

        print "done.\n";

        $this->assertTrue($crawler->filter('html:contains("Event list")')->count() == 1);
    }


    public function testIndexFromMainNav()
    {
    	print " testing event index from main navigation ... ";

    	$crawler = $this->client->request('GET', '/');
    
    	$link = $crawler->filter('a:contains("events")')->eq(0)->link();
    
    	$landing = $this->client->click($link);

    	print "done.\n";

    	$this->assertTrue($landing->filter('html:contains("Event list")')->count() == 1);
    }


    public function testNew()
    {
    	print " testing event new with a direct get ... ";

    	$crawler = $this->client->request('GET', '/event/new');

    	print "done.\n";

    	$this->assertTrue($crawler->filter('html:contains("add an event")')->count() == 1);
    }
    
    
    public function testNewFromIndex()
    {
    	print " testing event new from event list ... ";

    	$crawler = $this->client->request('GET', '/event/');

    	$link = $crawler->filter('a:contains("new event")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	print "done.\n\n";

    	$this->assertTrue($landing->filter('html:contains("add an event")')->count() == 1);
    }
}
