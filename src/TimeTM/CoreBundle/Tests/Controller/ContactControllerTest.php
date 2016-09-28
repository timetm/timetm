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

        $this->assertTrue($crawler->filter('html:contains("contact list")')->count() == 1);

        print "done.\n";
    }

    public function testIndexAjax() {

        printf("%-75s", " contact index with a ajax ... ");

        $crawler = $this->client->request('GET', '/contact/', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->assertTrue($crawler->filter('html:contains("contact list")')->count() == 1);

        print "done.\n";
    }

    public function testIndexFromMainNav() {

        printf("%-75s", " contact index from main navigation ... ");

    	$crawler = $this->client->request('GET', '/');

    	$link = $crawler->filter('a:contains("contacts")')->eq(0)->link();

    	$landing = $this->client->click($link);

		$this->assertTrue($landing->filter('html:contains("contact list")')->count() == 1);

        print "done.\n";
    }

    public function testNew() {

        printf("%-75s", " contact new with a direct get ... ");

    	$crawler = $this->client->request('GET', '/contact/new');

    	$this->assertTrue($crawler->filter('html:contains("new contact")')->count() == 1);

    	print "done.\n";
    }

    public function testNewAjax() {

        printf("%-75s", " contact new with ajax ... ");

    	$crawler = $this->client->request('GET', '/contact/new', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

    	$this->assertTrue($crawler->filter('html:contains("new contact")')->count() == 1);

    	print "done.\n";
    }

    public function testNewFromIndex() {

        printf("%-75s", " contact new from contact list ... ");

    	$crawler = $this->client->request('GET', '/contact/');

    	$link = $crawler->filter('a:contains("new contact")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	$this->assertTrue($landing->filter('html:contains("new contact")')->count() == 1);

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
        $this->client->followRedirect();
        $this->assertContains(
            'contact details',
            $this->client->getResponse()->getContent()
        );
        $this->assertContains(
            'test user last',
            $this->client->getResponse()->getContent()
        );

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

        $this->assertContains(
            'This value should not be blank.',
            $this->client->getResponse()->getContent()
        );

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

        $response = $this->client->getResponse();
        $this->assertContains('le compte existe déjà', $response->getContent());

    	print "done.\n";
    }

    public function testEdit() {

        printf("%-75s", " contact edit with a direct get ... ");

    	$crawler = $this->client->request('GET', '/contact/1/edit');

    	$this->assertTrue($crawler->filter('html:contains("edit contact")')->count() == 1);

    	print "done.\n";
    }

    public function testEditAjax() {

        printf("%-75s", " contact edit with ajax ... ");

        $crawler = $this->client->request('GET', '/contact/1/edit', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->assertTrue($crawler->filter('html:contains("edit contact")')->count() == 1);

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
        $this->client->followRedirect();
        $this->assertContains(
            'contact details',
            $this->client->getResponse()->getContent()
        );
        $this->assertContains(
            'test user last updated',
            $this->client->getResponse()->getContent()
        );

        print "done.\n\n\n";
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

        $this->assertContains(
            'This value should not be blank.',
            $this->client->getResponse()->getContent()
        );

        print "done.\n\n\n";
    }

}
