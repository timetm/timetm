<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AgendaControllerTest extends WebTestCase {

	public function setUp() {

		$this->client = static::createClient(array(), array(
		    'PHP_AUTH_USER' => 'admin',
		    'PHP_AUTH_PW'   => '1234',
		));
	}

    public function testIndex() {

        print " -- AGENDA ---------------------------------------------------------------------\n\n.";
        printf("%-75s", " agenda index with a direct get ... ");

        $crawler = $this->client->request('GET', '/agenda/');

        $this->assertTrue($crawler->filter('html:contains("agenda list")')->count() == 1);

        print "done.\n";
    }

    /**
     * GET          /
     * CLIC         admin
     * CLIC         edit agandas
     */
    public function testIndexFromProfile() {

        printf("%-75s", " agenda index from profile ... ");

    	$crawler = $this->client->request('GET', '/');

    	$link = $crawler->filter('a:contains("admin")')->eq(0)->link();

    	$landing = $this->client->click($link);

		$this->assertTrue($landing->filter('html:contains("profile")')->count() == 1);

        $link = $landing->filter('a:contains("edit agendas")')->eq(0)->link();

        $landing = $this->client->click($link);

        $this->assertTrue($landing->filter('html:contains("agenda list")')->count() == 1);

    	print "done.\n";
    }

    public function testNew() {

        printf("%-75s", " agenda new with a direct get ... ");

    	$crawler = $this->client->request('GET', '/agenda/new');

    	$this->assertTrue($crawler->filter('html:contains("new agenda")')->count() == 1);

        print "done.\n";
    }

    public function testNewFromIndex() {

        printf("%-75s", " agenda new from agenda list ... ");

    	$crawler = $this->client->request('GET', '/agenda/');

    	$link = $crawler->filter('a:contains("new agenda")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	$this->assertTrue($landing->filter('html:contains("new agenda")')->count() == 1);

    	print "done.\n";
    }

    public function testCreate() {

        printf("%-75s", " agenda create with a direct post ... ");

    	$crawler = $this->client->request('GET', '/agenda/new');

    	$this->assertTrue($crawler->filter('html:contains("new agenda")')->count() == 1);

        $form = $crawler->selectButton('create')->form();

        $form['timetm_agendabundle_agenda[name]'] = 'test';
        $form['timetm_agendabundle_agenda[description]'] = 'test agenda';

        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();
        $this->assertContains(
            'agenda details',
            $this->client->getResponse()->getContent()
        );
        $this->assertContains(
            'test agenda',
            $this->client->getResponse()->getContent()
        );

    	print "done.\n";
    }

    public function testEdit() {

        printf("%-75s", " agenda edit with a direct get ... ");

    	$crawler = $this->client->request('GET', '/agenda/2/edit');

    	$this->assertTrue($crawler->filter('html:contains("edit agenda")')->count() == 1);

    	print "done.\n";
    }

    public function testUpdate() {

        printf("%-75s", " agenda update with a direct post ... ");

        $crawler = $this->client->request('GET', '/agenda/2/edit');

        $this->assertTrue($crawler->filter('html:contains("edit agenda")')->count() == 1);

        $form = $crawler->selectButton('update')->form();

        $form['timetm_agendabundle_agenda[name]'] = 'test';
        $form['timetm_agendabundle_agenda[description]'] = 'test agenda updated';

        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();
        $this->assertContains(
            'agenda details',
            $this->client->getResponse()->getContent()
        );
        $this->assertContains(
            'test agenda updated',
            $this->client->getResponse()->getContent()
        );

        print "done.\n\n\n";
    }
}
