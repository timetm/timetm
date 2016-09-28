<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

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

        $this->assertTrue($crawler->filter('html:contains("event list")')->count() == 1);
    }

    public function testIndexAjax() {

        printf("%-75s", " event index with a ajax ... ");

        $crawler = $this->client->request('GET', '/event/', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->assertTrue($crawler->filter('html:contains("event list")')->count() == 1);

        print "done.\n";
    }

    public function testIndexFromMainNav() {

        printf("%-75s", " event index from main navigation ... ");

    	$crawler = $this->client->request('GET', '/');

    	$link = $crawler->filter('a:contains("events")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	$this->assertTrue($landing->filter('html:contains("event list")')->count() == 1);

        print "done.\n";
    }

    public function testNew() {

        printf("%-75s", " event new with a direct get ... ");

    	$crawler = $this->client->request('GET', '/event/new');

    	$this->assertTrue($crawler->filter('html:contains("new event")')->count() == 1);

        print "done.\n";
    }

    public function testNewAjax() {

        printf("%-75s", " event new with ajax ... ");

    	$crawler = $this->client->request('GET', '/event/new', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));


    	$this->assertTrue($crawler->filter('html:contains("new event")')->count() == 1);

        print "done.\n";
    }

    public function testNewFromIndex() {

        printf("%-75s", " event new from event list ... ");

    	$crawler = $this->client->request('GET', '/event/');

    	$link = $crawler->filter('a:contains("new event")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	$this->assertTrue($landing->filter('html:contains("new event")')->count() == 1);

        print "done.\n";
    }

    public function testCreate() {

        $container = $this->client->getContainer();
        $session = $container->get('session');
        $session->set('ttm/event/referer', '/month/');
        $session->set('ttm/agenda/current', '1');
        $session->save();

        printf("%-75s", " event create with a direct post ... ");

        $crawler = $this->client->request('GET', '/event/new');

        $this->assertTrue($crawler->filter('html:contains("new event")')->count() == 1);

        $form = $crawler->selectButton('create')->form();

        $startDate = date('d/m/Y') . " 09:00";
        $endDate = date('d/m/Y') . " 10:00";

        $form['timetm_eventbundle_event[title]'] = 'test title';
        $form['timetm_eventbundle_event[place]'] = 'test place';
        $form['timetm_eventbundle_event[agenda]'] = '1';
        $form['timetm_eventbundle_event[startdate]'] = $startDate;
        $form['timetm_eventbundle_event[enddate]'] = $endDate;

        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();
        $this->assertContains(
            '09:00',
            $this->client->getResponse()->getContent()
        );
        $this->assertContains(
            'test title',
            $this->client->getResponse()->getContent()
        );

    	print "done.\n";
    }

    public function testCreateFormError() {

        $container = $this->client->getContainer();
        $session = $container->get('session');
        $session->set('ttm/event/referer', '/month/');
        $session->set('ttm/agenda/current', '1');
        $session->save();

        printf("%-75s", " event create with a direct post INVALID DATA ... ");

        $crawler = $this->client->request('GET', '/event/new');

        $this->assertTrue($crawler->filter('html:contains("new event")')->count() == 1);

        $form = $crawler->selectButton('create')->form();

        $startDate = date('d/m/Y') . " 09:00";
        $endDate = date('d/m/Y') . " 10:00";

        $form['timetm_eventbundle_event[title]'] = '';
        $form['timetm_eventbundle_event[place]'] = 'test place';
        $form['timetm_eventbundle_event[agenda]'] = '1';
        $form['timetm_eventbundle_event[startdate]'] = $startDate;
        $form['timetm_eventbundle_event[enddate]'] = $endDate;

        $crawler = $this->client->submit($form);

        $this->assertContains(
            'This value should not be blank.',
            $this->client->getResponse()->getContent()
        );

    	print "done.\n";
    }

    public function testEdit() {

        printf("%-75s", " event edit with a direct get ... ");

    	$crawler = $this->client->request('GET', '/event/1/edit');

    	$this->assertTrue($crawler->filter('html:contains("edit event")')->count() == 1);

    	print "done.\n";
    }

    public function testEditAjax() {

        printf("%-75s", " event edit with ajax ... ");

    	$crawler = $this->client->request('GET', '/event/1/edit', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

    	$this->assertTrue($crawler->filter('html:contains("edit event")')->count() == 1);

    	print "done.\n";
    }

    public function testUpdate() {

        printf("%-75s", " event update with a direct post ... ");

        $crawler = $this->client->request('GET', '/event/1/edit');

        $this->assertTrue($crawler->filter('html:contains("edit event")')->count() == 1);

        $form = $crawler->selectButton('update')->form();

        $startDate = date('d/m/Y') . " 09:00";
        $endDate = date('d/m/Y') . " 10:00";

        $form['timetm_eventbundle_event[title]'] = 'test title updated';
        $form['timetm_eventbundle_event[place]'] = 'test place';
        $form['timetm_eventbundle_event[agenda]'] = '1';
        $form['timetm_eventbundle_event[startdate]'] = $startDate;
        $form['timetm_eventbundle_event[enddate]'] = $endDate;

        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();
        $this->assertContains(
            'event details',
            $this->client->getResponse()->getContent()
        );
        $this->assertContains(
            'test title updated',
            $this->client->getResponse()->getContent()
        );

        print "done.\n\n\n";
    }

    public function testUpdateFormError() {

        printf("%-75s", " event update with a direct post INVALID DATA... ");

        $crawler = $this->client->request('GET', '/event/1/edit');

        $this->assertTrue($crawler->filter('html:contains("edit event")')->count() == 1);

        $form = $crawler->selectButton('update')->form();

        $startDate = date('d/m/Y') . " 09:00";
        $endDate = date('d/m/Y') . " 10:00";

        $form['timetm_eventbundle_event[title]'] = '';
        $form['timetm_eventbundle_event[place]'] = 'test place';
        $form['timetm_eventbundle_event[agenda]'] = '1';
        $form['timetm_eventbundle_event[startdate]'] = $startDate;
        $form['timetm_eventbundle_event[enddate]'] = $endDate;

        $crawler = $this->client->submit($form);

        $this->assertContains(
            'This value should not be blank.',
            $this->client->getResponse()->getContent()
        );

        print "done.\n\n\n";
    }

}
