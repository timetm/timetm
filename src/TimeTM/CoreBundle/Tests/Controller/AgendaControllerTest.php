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

        $this->_commonTests($crawler, 'Agendas', 'agenda list');

        print "done.\n";
    }

    public function testIndexAjax() {

        printf("%-75s", " agenda index with a ajax ... ");

        $crawler = $this->client->request('GET', '/agenda/', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->_commonTests($crawler, 'Agendas', 'agenda list');

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

        $this->_commonTests($landing, 'Agendas', 'agenda list');

    	print "done.\n";
    }

    public function testNew() {

        printf("%-75s", " agenda new with a direct get ... ");

    	$crawler = $this->client->request('GET', '/agenda/new');

    	$this->_commonTests($crawler, 'New agenda', 'new agenda');

        print "done.\n";
    }

    public function testNewAjax() {

        printf("%-75s", " agenda new with ajax ... ");

        $crawler = $this->client->request('GET', '/agenda/new', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->_commonTests($crawler, 'New agenda', 'new agenda');

        print "done.\n";
    }

    public function testNewFromIndex() {

        printf("%-75s", " agenda new from agenda list ... ");

    	$crawler = $this->client->request('GET', '/agenda/');

    	$link = $crawler->filter('a:contains("new agenda")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	$this->_commonTests($landing, 'New agenda', 'new agenda');

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

        $crawler = $this->client->followRedirect();

        $this->_commonTests($crawler, 'Agenda details', 'agenda details');

        // check table content
        $this->assertTrue($crawler->filter('table:contains("test agenda")')->count() == 1);

    	print "done.\n";
    }

    public function testEdit() {

        printf("%-75s", " agenda edit with a direct get ... ");

    	$crawler = $this->client->request('GET', '/agenda/2/edit');

    	$this->_commonTests($crawler, 'Edit agenda', 'edit agenda');

    	print "done.\n";
    }

    public function testEditAjax() {

        printf("%-75s", " agenda edit with ajax ... ");

        $crawler = $this->client->request('GET', '/agenda/1/edit', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->_commonTests($crawler, 'Edit agenda', 'edit agenda');

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

        $crawler = $this->client->followRedirect();

        $this->_commonTests($crawler, 'Agenda details', 'agenda details');

        // check table content
        $this->assertTrue($crawler->filter('table:contains("test agenda updated")')->count() == 1);

        print "done.\n\n\n";
    }

    private function _commonTests($crawler, $title, $content) {

        // title
        $this->assertTrue($crawler->filter("title:contains(\"$title\")")->count() == 1);

        // content
        $this->assertTrue($crawler->filter(".listContainer h1:contains(\"$content\")")->count() == 1);
    }
}
