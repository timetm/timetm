<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase {

    public function setUp() {

		$this->client = static::createClient(array(), array(
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => '1234',
		));
	}

    public function testIndex() {

        print " -- TASK -----------------------------------------------------------------------\n\n.";
        printf("%-75s", " task index with a direct get ... ");

        $crawler = $this->client->request('GET', '/task/');

        print "done.\n";

        $this->assertTrue($crawler->filter('html:contains("task list")')->count() == 1);
    }

    public function testIndexAjax() {

        printf("%-75s", " task index with a ajax ... ");

        $crawler = $this->client->request('GET', '/task/', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->assertTrue($crawler->filter('html:contains("task list")')->count() == 1);

        print "done.\n";
    }

    public function testIndexFromMainNav() {

        printf("%-75s", " task index from main navigation ... ");

    	$crawler = $this->client->request('GET', '/');

    	$link = $crawler->filter('a:contains("tasks")')->eq(0)->link();

    	$landing = $this->client->click($link);

		$this->assertTrue($landing->filter('html:contains("task list")')->count() == 1);

        print "done.\n";
    }

    public function testNew() {

        printf("%-75s", " task new with a direct get ... ");

    	$crawler = $this->client->request('GET', '/task/new');

    	$this->assertTrue($crawler->filter('html:contains("new task")')->count() == 1);

    	print "done.\n";
    }

    public function testNewAjax() {

        printf("%-75s", " task new with ajax ... ");

        $crawler = $this->client->request('GET', '/task/new', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->assertTrue($crawler->filter('html:contains("new task")')->count() == 1);

        print "done.\n";
    }

    public function testNewFromIndex() {

        printf("%-75s", " task new from task list ... ");

    	$crawler = $this->client->request('GET', '/task/');

    	$link = $crawler->filter('a:contains("new task")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	$this->assertTrue($landing->filter('html:contains("new task")')->count() == 1);

        print "done.\n";
    }

    public function testCreate() {

        printf("%-75s", " task create with a direct post ... ");

    	$crawler = $this->client->request('GET', '/task/new');

    	$this->assertTrue($crawler->filter('html:contains("new task")')->count() == 1);

        $form = $crawler->selectButton('create')->form();

        $date = date('d/m/Y');

        $form['task[title]'] = 'test task';
        $form['task[duedate]'] = $date;

        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();
        $this->assertContains(
            'task details',
            $this->client->getResponse()->getContent()
        );
        $this->assertContains(
            $date,
            $this->client->getResponse()->getContent()
        );

    	print "done.\n";
    }

    public function testCreateFormError() {

        printf("%-75s", " task create with a direct post INVALID DATA... ");

    	$crawler = $this->client->request('GET', '/task/new');

    	$this->assertTrue($crawler->filter('html:contains("new task")')->count() == 1);

        $form = $crawler->selectButton('create')->form();

        $date = date('d/m/Y');

        $form['task[title]'] = '';
        $form['task[duedate]'] = $date;

        $crawler = $this->client->submit($form);

        $this->assertContains(
            'This value should not be blank.',
            $this->client->getResponse()->getContent()
        );

    	print "done.\n";
    }

    public function testEdit() {

        printf("%-75s", " task edit with a direct get ... ");

    	$crawler = $this->client->request('GET', '/task/1/edit');

    	$this->assertTrue($crawler->filter('html:contains("edit task")')->count() == 1);

    	print "done.\n";
    }

    public function testEditAjax() {

        printf("%-75s", " task edit with ajax ... ");

        $crawler = $this->client->request('GET', '/task/1/edit', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->assertTrue($crawler->filter('html:contains("edit task")')->count() == 1);

        print "done.\n";
    }

    public function testUpdate() {

        printf("%-75s", " task update with a direct post ... ");

        $crawler = $this->client->request('GET', '/task/1/edit');

        $this->assertTrue($crawler->filter('html:contains("edit task")')->count() == 1);

        $form = $crawler->selectButton('update')->form();

        $date = date('d/m/Y');

        $form['task[title]'] = 'test task updated';
        $form['task[duedate]'] = $date;

        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();
        $this->assertContains(
            'task details',
            $this->client->getResponse()->getContent()
        );
        $this->assertContains(
            'test task updated',
            $this->client->getResponse()->getContent()
        );

        print "done.\n\n\n";
    }

    public function testUpdateFormError() {

        printf("%-75s", " task update with a direct post INVALID DATA ... ");

        $crawler = $this->client->request('GET', '/task/1/edit');

        $this->assertTrue($crawler->filter('html:contains("edit task")')->count() == 1);

        $form = $crawler->selectButton('update')->form();

        $date = date('d/m/Y');

        $form['task[title]'] = '';
        $form['task[duedate]'] = $date;

        $crawler = $this->client->submit($form);

        $this->assertContains(
            'This value should not be blank.',
            $this->client->getResponse()->getContent()
        );

        print "done.\n\n\n";
    }
}
