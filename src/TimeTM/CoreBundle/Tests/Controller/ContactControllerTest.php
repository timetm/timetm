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

    public function testIndex() {

        printf("%-75s", " contact index with a direct get ... ");

        $crawler = $this->client->request('GET', '/contact/');

        $this->assertTrue($crawler->filter('html:contains("Contact list")')->count() == 1);

        print "done.\n";
    }


    public function testIndexFromMainNav() {

        printf("%-75s", " contact index from main navigation ... ");

    	$crawler = $this->client->request('GET', '/');

    	$link = $crawler->filter('a:contains("contacts")')->eq(0)->link();

    	$landing = $this->client->click($link);

		$this->assertTrue($landing->filter('html:contains("Contact list")')->count() == 1);

        print "done.\n";
    }


    public function testNew() {

        printf("%-75s", " contact new with a direct get ... ");

    	$crawler = $this->client->request('GET', '/contact/new');

    	$this->assertTrue($crawler->filter('html:contains("add a contact")')->count() == 1);

    	print "done.\n";
    }


    public function testNewFromIndex() {

        printf("%-75s", " contact new from contact list ... ");

    	$crawler = $this->client->request('GET', '/contact/');

    	$link = $crawler->filter('a:contains("new contact")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	$this->assertTrue($landing->filter('html:contains("add a contact")')->count() == 1);

        print "done.\n\n";
    }
}
