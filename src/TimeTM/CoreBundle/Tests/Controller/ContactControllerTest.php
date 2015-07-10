<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
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
    	print " testing contact index with a direct get ... ";

        $crawler = $this->client->request('GET', '/contact/');

        print "done.\n";

        $this->assertTrue($crawler->filter('html:contains("Contact list")')->count() == 1);
    }


    public function testIndexFromMainNav()
    {
    	print " testing contact index from main navigation ... ";

    	$crawler = $this->client->request('GET', '/');

    	$link = $crawler->filter('a:contains("contacts")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	print "done.\n";

		$this->assertTrue($landing->filter('html:contains("Contact list")')->count() == 1);
    }


    public function testNew()
    {
    	print " testing contact new with a direct get ... ";

    	$crawler = $this->client->request('GET', '/contact/new');

    	print "done.\n";

    	$this->assertTrue($crawler->filter('html:contains("Contact creation")')->count() == 1);
    }


    public function testNewFromIndex()
    {
    	print " testing contact new from contact list ... ";

    	$crawler = $this->client->request('GET', '/contact/');

    	$link = $crawler->filter('a:contains("new contact")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	print "done.\n\n";

    	$this->assertTrue($landing->filter('html:contains("Contact creation")')->count() == 1);
    }
}
