<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AgendaControllerTest extends WebTestCase
{

	public function setUp() {

		$this->client = static::createClient(array(), array(
		    'PHP_AUTH_USER' => 'admin',
		    'PHP_AUTH_PW'   => '1234',
		));
	}

    public function testIndex() {

        printf("%-75s", " agenda index with a direct get ... ");

        $crawler = $this->client->request('GET', '/agenda/');

        $this->assertTrue($crawler->filter('html:contains("Agenda list")')->count() == 1);

        print "done.\n";
    }

    public function testIndexFromProfile() {

        printf("%-75s", " agenda index from profile ... ");

    	$crawler = $this->client->request('GET', '/');

    	$link = $crawler->filter('a:contains("admin")')->eq(0)->link();

    	$landing = $this->client->click($link);

		$this->assertTrue($landing->filter('html:contains("profile")')->count() == 1);

        $link = $landing->filter('a:contains("edit agendas")')->eq(0)->link();

        $landing = $this->client->click($link);

        $this->assertTrue($landing->filter('html:contains("Agenda list")')->count() == 1);

    	print "done.\n";
    }

    public function testNew() {

        printf("%-75s", " agenda new with a direct get ... ");

    	$crawler = $this->client->request('GET', '/agenda/new');

    	$this->assertTrue($crawler->filter('html:contains("Agenda creation")')->count() == 1);

        print "done.\n";
    }

    public function testNewFromIndex() {

        printf("%-75s", " agenda new from agenda list ... ");

    	$crawler = $this->client->request('GET', '/agenda/');

    	$link = $crawler->filter('a:contains("new agenda")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	$this->assertTrue($landing->filter('html:contains("Agenda creation")')->count() == 1);

    	print "done.\n\n";
    }

}
