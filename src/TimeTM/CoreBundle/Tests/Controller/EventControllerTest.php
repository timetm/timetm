<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventControllerTest extends WebTestCase {

	public function setUp() {

		$this->client = static::createClient(array(), array(
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => '1234',
		));
	}

    public function testIndex() {

        print " -- EVENT ----------------------------------------------------------------------\n\n.";
        printf("%-75s", " event index with a direct get ... ");

        $crawler = $this->client->request('GET', '/event/');

        print "done.\n";

        $this->assertTrue($crawler->filter('html:contains("Event list")')->count() == 1);
    }


    public function testIndexFromMainNav() {

        printf("%-75s", " event index from main navigation ... ");

    	$crawler = $this->client->request('GET', '/');

    	$link = $crawler->filter('a:contains("events")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	$this->assertTrue($landing->filter('html:contains("Event list")')->count() == 1);

        print "done.\n";
    }


    public function testNew() {

        printf("%-75s", " event new with a direct get ... ");

    	$crawler = $this->client->request('GET', '/event/new');

    	$this->assertTrue($crawler->filter('html:contains("add an event")')->count() == 1);

        print "done.\n";
    }


    public function testNewFromIndex() {

        printf("%-75s", " event new from event list ... ");

    	$crawler = $this->client->request('GET', '/event/');

    	$link = $crawler->filter('a:contains("new event")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	$this->assertTrue($landing->filter('html:contains("add an event")')->count() == 1);

        print "done.\n\n";
    }
}
