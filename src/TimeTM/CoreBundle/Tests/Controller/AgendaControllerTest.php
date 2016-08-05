<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AgendaControllerTest extends WebTestCase
{

	public function setUp()
	{
		$this->client = static::createClient(array(), array(
		    'PHP_AUTH_USER' => "admin",
		    'PHP_AUTH_PW'   => "1234",
		));
	}

    public function testIndex()
    {
    	print " testing agenda index with a direct get ... ";

        $crawler = $this->client->request('GET', '/agenda/');

        print "done.\n";

        $this->assertTrue($crawler->filter('html:contains("Agenda list")')->count() == 1);
    }

    public function testNew()
    {
    	print " testing agenda new with a direct get ... ";

    	$crawler = $this->client->request('GET', '/agenda/new');

    	print "done.\n";

    	$this->assertTrue($crawler->filter('html:contains("Agenda creation")')->count() == 1);
    }

    public function testNewFromIndex()
    {
    	print " testing agenda new from agenda list ... ";

    	$crawler = $this->client->request('GET', '/agenda/');

    	$link = $crawler->filter('a:contains("new agenda")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	print "done.\n\n";

    	$this->assertTrue($landing->filter('html:contains("Agenda creation")')->count() == 1);
    }

}
