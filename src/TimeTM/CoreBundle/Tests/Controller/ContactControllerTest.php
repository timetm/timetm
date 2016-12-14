<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase {

	public function setUp() {

		$this->client = static::createClient(array(), array(
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => '1234',
		));
	}

    public function testIndex() {

        print " -- CONTACT --------------------------------------------------------------------\n\n.";
        printf("%-75s", " contact index with a direct get ... ");

        $crawler = $this->client->request('GET', '/contact/');

        $this->_commonTests($crawler, 'Contacts', 'contact list');

        print "done.\n";
    }

    public function testIndexAjax() {

        printf("%-75s", " contact index with a ajax ... ");

        $crawler = $this->client->request('GET', '/contact/', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->_commonTests($crawler, 'Contacts', 'contact list');

        print "done.\n";
    }

    public function testIndexFromMainNav() {

        printf("%-75s", " contact index from main navigation ... ");

    	$crawler = $this->client->request('GET', '/');

    	$link = $crawler->filter('a:contains("contacts")')->eq(0)->link();

    	$landing = $this->client->click($link);

		$this->_commonTests($landing, 'Contacts', 'contact list');

        print "done.\n";
    }

    public function testNew() {

        printf("%-75s", " contact new with a direct get ... ");

    	$crawler = $this->client->request('GET', '/contact/new');

    	$this->_commonTests($crawler, 'New contact', 'new contact');

    	print "done.\n";
    }

    public function testNewAjax() {

        printf("%-75s", " contact new with ajax ... ");

    	$crawler = $this->client->request('GET', '/contact/new', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

    	$this->_commonTests($crawler, 'New contact', 'new contact');

    	print "done.\n";
    }

    public function testNewFromIndex() {

        printf("%-75s", " contact new from contact list ... ");

    	$crawler = $this->client->request('GET', '/contact/');

    	$link = $crawler->filter('a:contains("new contact")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	$this->_commonTests($landing, 'New contact', 'new contact');

        print "done.\n";
    }

    public function testCreate() {

        printf("%-75s", " contact create with a direct post ... ");

    	$crawler = $this->client->request('GET', '/contact/new');

    	$this->assertTrue($crawler->filter('html:contains("new contact")')->count() == 1);

        $form = $crawler->selectButton('create')->form();

        $form['timetm_contactbundle_contact[lastname]'] = 'test user last';
        $form['timetm_contactbundle_contact[firstname]'] = 'test user first';
        $form['timetm_contactbundle_contact[email]'] = 'test user email';
        $form['timetm_contactbundle_contact[phone]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());

        $crawler = $this->client->followRedirect();

        $this->_commonTests($crawler, 'Contact details', 'contact details');

        // check table content
        $this->assertTrue($crawler->filter('table:contains("lastname")')->count() == 1);
        $this->assertTrue($crawler->filter('table:contains("test user last")')->count() == 1);

    	print "done.\n";
    }

    public function testCreateFormError() {

        printf("%-75s", " contact create with a direct post INVALID DATA... ");

    	$crawler = $this->client->request('GET', '/contact/new');

    	$this->assertTrue($crawler->filter('html:contains("new contact")')->count() == 1);

        $form = $crawler->selectButton('create')->form();

        $form['timetm_contactbundle_contact[lastname]'] = '';
        $form['timetm_contactbundle_contact[firstname]'] = 'test user first';
        $form['timetm_contactbundle_contact[email]'] = 'test user email';
        $form['timetm_contactbundle_contact[phone]'] = '';

        $crawler = $this->client->submit($form);

        $this->_commonTests($crawler, 'New contact', 'new contact');

        // error message
        $this->assertTrue($crawler->filter('html:contains("This value should not be blank")')->count() == 1);

    	print "done.\n";
    }

    public function testCreateExistingContact() {

        printf("%-75s", " contact create with a direct post EXISTING USER ... ");

    	$crawler = $this->client->request('GET', '/contact/new');

    	$this->assertTrue($crawler->filter('html:contains("new contact")')->count() == 1);

        $form = $crawler->selectButton('create')->form();

        $form['timetm_contactbundle_contact[lastname]'] = 'test user last';
        $form['timetm_contactbundle_contact[firstname]'] = 'test user first';
        $form['timetm_contactbundle_contact[email]'] = 'test user email';
        $form['timetm_contactbundle_contact[phone]'] = '';

        $crawler = $this->client->submit($form);

        $this->_commonTests($crawler, 'New contact', 'new contact');

        // error message
        $this->assertTrue($crawler->filter('html:contains("the account already exists")')->count() == 1);

    	print "done.\n";
    }

    public function testEdit() {

        printf("%-75s", " contact edit with a direct get ... ");

    	$crawler = $this->client->request('GET', '/contact/1/edit');

    	$this->_commonTests($crawler, 'Edit contact', 'edit contact');

    	print "done.\n";
    }

    public function testEditAjax() {

        printf("%-75s", " contact edit with ajax ... ");

        $crawler = $this->client->request('GET', '/contact/1/edit', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->_commonTests($crawler, 'Edit contact', 'edit contact');

        print "done.\n";
    }

    public function testUpdate() {

        printf("%-75s", " contact update with a direct post ... ");

        $crawler = $this->client->request('GET', '/contact/1/edit');

        $this->assertTrue($crawler->filter('html:contains("edit contact")')->count() == 1);

        $form = $crawler->selectButton('update')->form();

        $form['timetm_contactbundle_contact[lastname]'] = 'test user last updated';
        $form['timetm_contactbundle_contact[firstname]'] = 'test user first updated';
        $form['timetm_contactbundle_contact[email]'] = 'test user email updated';
        $form['timetm_contactbundle_contact[phone]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());

        $crawler = $this->client->followRedirect();

        $this->_commonTests($crawler, 'Contact details', 'contact details');

        // check table content
        $this->assertTrue($crawler->filter('table:contains("lastname")')->count() == 1);
        $this->assertTrue($crawler->filter('table:contains("test user last updated")')->count() == 1);

        print "done.\n";
    }

    public function testUpdateFormError() {

        printf("%-75s", " contact update with a direct post INVALID DATA ... ");

        $crawler = $this->client->request('GET', '/contact/1/edit');

        $this->assertTrue($crawler->filter('html:contains("edit contact")')->count() == 1);

        $form = $crawler->selectButton('update')->form();

        $form['timetm_contactbundle_contact[lastname]'] = '';
        $form['timetm_contactbundle_contact[firstname]'] = 'test user first updated';
        $form['timetm_contactbundle_contact[email]'] = 'test user email updated';
        $form['timetm_contactbundle_contact[phone]'] = '';

        $crawler = $this->client->submit($form);

        $this->_commonTests($crawler, 'Edit contact', 'edit contact');

        // error message
        $this->assertTrue($crawler->filter('html:contains("This value should not be blank")')->count() == 1);

        print "done.\n\n\n";
    }


    private function _commonTests($crawler, $title, $content) {

        // title
        $this->assertTrue($crawler->filter("title:contains(\"$title\")")->count() == 1);

        // content
        $this->assertTrue($crawler->filter(".listContainer h1:contains(\"$content\")")->count() == 1);

        // panel
        $dateDisplay = date("F") . " " . date("Y");
        $this->assertTrue($crawler->filter("#dateDisplay:contains(\"$dateDisplay\")")->count() == 1);
    }
}
