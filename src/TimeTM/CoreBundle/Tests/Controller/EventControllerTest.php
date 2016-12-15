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

    /**
     *  INDEX  ----------------------------------------------------------------
     */
    public function testIndex() {

        print " -- EVENT ----------------------------------------------------------------------\n\n.";
        printf("%-75s", " event index with a direct get ... ");

        $crawler = $this->client->request('GET', '/event/');

        $this->_commonTests($crawler, 'Events', 'event list');

        print "done.\n";
    }

    public function testIndexAjax() {

        printf("%-75s", " event index with a ajax ... ");

        $crawler = $this->client->request('GET', '/event/', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->_commonTests($crawler, 'Events', 'event list');

        print "done.\n";
    }

    public function testIndexFromMainNav() {

        printf("%-75s", " event index from main navigation ... ");

    	$crawler = $this->client->request('GET', '/');

    	$link = $crawler->filter('a:contains("events")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	$this->_commonTests($landing, 'Events', 'event list');

        print "done.\n";
    }

    /**
     *  NEW  ------------------------------------------------------------------
     */
    public function testNew() {

        $formDate = date("d/m/Y");

        printf("%-75s", " event new with a direct get ... ");

    	$crawler = $this->client->request('GET', '/event/new');

    	$this->_commonTests($crawler, 'New event', 'new event', $formDate);

        print "done.\n";
    }

    public function testNewWithDate() {

        $date = date("Y/m/d");
        $formDate = date("d/m/Y");

        printf("%-75s", " event new with a direct get and date /event/new/$date ... ");

    	$crawler = $this->client->request('GET', '/event/new/'.$date);

    	$this->_commonTests($crawler, 'New event', 'new event', $formDate);

        print "done.\n";
    }

    public function testNewWithDateAndTime() {

        $date = date("Y/m/d/H/00");
        $formDate = date("d/m/Y H:00");

        printf("%-75s", " event new with a direct get and date /event/new/$date ... ");

    	$crawler = $this->client->request('GET', '/event/new/'.$date);

    	$this->_commonTests($crawler, 'New event', 'new event', $formDate);

        print "done.\n";
    }

    public function testNewAjax() {

        $formDate = date("d/m/Y");

        printf("%-75s", " event new with ajax ... ");

    	$crawler = $this->client->request('GET', '/event/new', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

    	$this->_commonTests($crawler, 'New event', 'new event', $formDate);

        print "done.\n";
    }

    public function testNewWithDateAjax() {

        $date = date("Y/m/d");
        $formDate = date("d/m/Y");

        printf("%-75s", " event new with ajax and date /event/new/$date ... ");

    	$crawler = $this->client->request('GET', '/event/new/'.$date, array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

    	$this->_commonTests($crawler, 'New event', 'new event', $formDate);

        print "done.\n";
    }

    public function testNewWithDateAndTimeAjax() {

        $date = date("Y/m/d/H/00");
        $formDate = date("d/m/Y H:00");

        printf("%-75s", " event new with ajax and date /event/new/$date ... ");

    	$crawler = $this->client->request('GET', '/event/new/'.$date, array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

    	$this->_commonTests($crawler, 'New event', 'new event', $formDate);

        print "done.\n";
    }

    public function testNewFromIndex() {

        $formDate = date("d/m/Y");

        printf("%-75s", " event new from event list ... ");

    	$crawler = $this->client->request('GET', '/event/');

    	$link = $crawler->filter('a:contains("new event")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	$this->_commonTests($landing, 'New event', 'new event', $formDate);

        print "done.\n";
    }

    /**
     *  CREATE  ---------------------------------------------------------------
     */
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

        $crawler = $this->client->followRedirect();

        $date = date('F Y');
        $this->assertTrue($crawler->filter("title:contains(\"$date\")")->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("test title")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("09:00")')->count() == 1);

    	print "done.\n";
    }

    public function testCreateFormError() {

        $formDate = date("d/m/Y");

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

        $this->_commonTests($crawler, 'New event', 'new event', $formDate);

        $this->assertTrue($crawler->filter('html:contains("This value should not be blank")')->count() == 1);

    	print "done.\n";
    }

    /**
     *  EDIT  -----------------------------------------------------------------
     */
    public function testEdit() {

        $formDate = date("d/m/Y");

        printf("%-75s", " event edit with a direct get ... ");

    	$crawler = $this->client->request('GET', '/event/1/edit');

    	$this->_commonTests($crawler, 'Edit event', 'edit event');

        // get form
        $form = $crawler->selectButton('update')->form();

        // get date value
        $dateValue = $form->get('timetm_eventbundle_event[startdate]')->getValue();

        $this->assertContains($formDate, $dateValue);

    	print "done.\n";
    }

    public function testEditAjax() {

        $formDate = date("d/m/Y");

        printf("%-75s", " event edit with ajax ... ");

    	$crawler = $this->client->request('GET', '/event/1/edit', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->_commonTests($crawler, 'Edit event', 'edit event');

        // get form
        $form = $crawler->selectButton('update')->form();

        // get date value
        $dateValue = $form->get('timetm_eventbundle_event[startdate]')->getValue();

        $this->assertContains($formDate, $dateValue);

    	print "done.\n";
    }

    /**
     *  UPDATE  ---------------------------------------------------------------
     */
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

        $crawler = $this->client->followRedirect();

        $this->_commonTests($crawler, 'Event details', 'event details');

        $this->assertTrue($crawler->filter('table:contains("test title updated")')->count() == 1);
        $this->assertTrue($crawler->filter('table:contains("09:00")')->count() == 1);

        $date = date('d M Y');
        $this->assertTrue($crawler->filter("table:contains(\"$date\")")->count() == 1);

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

        $this->_commonTests($crawler, 'Edit event', 'edit event');

        // error message
        $this->assertTrue($crawler->filter('html:contains("This value should not be blank")')->count() == 1);

        print "done.\n\n\n";
    }


    /**
     *  PRIVATE  --------------------------------------------------------------
     */
    private function _commonTests($crawler, $title, $content, $date = NULL) {

        // title
        $this->assertTrue($crawler->filter("title:contains(\"$title\")")->count() == 1);

        // content
        $this->assertTrue($crawler->filter(".listContainer h1:contains(\"$content\")")->count() == 1);

        if ($date) {

            // get form
            $form = $crawler->selectButton('create')->form();

            // get date value
            $dateValue = $form->get('timetm_eventbundle_event[startdate]')->getValue();

            $this->assertContains($date, $dateValue);
        }

        // panel
        $dateDisplay = date("F") . " " . date("Y");
        $this->assertTrue($crawler->filter("#dateDisplay:contains(\"$dateDisplay\")")->count() == 1);
    }
}
