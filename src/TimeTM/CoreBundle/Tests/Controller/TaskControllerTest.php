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

        $this->_commonTests($crawler, 'Tasks', 'task list');

        print "done.\n";
    }

    public function testIndexAjax() {

        printf("%-75s", " task index with a ajax ... ");

        $crawler = $this->client->request('GET', '/task/', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->_commonTests($crawler, 'Tasks', 'task list');

        print "done.\n";
    }

    public function testIndexFromMainNav() {

        printf("%-75s", " task index from main navigation ... ");

    	$crawler = $this->client->request('GET', '/');

    	$link = $crawler->filter('a:contains("tasks")')->eq(0)->link();

    	$landing = $this->client->click($link);

		$this->_commonTests($landing, 'Tasks', 'task list');

        print "done.\n";
    }

    public function testNew() {

        printf("%-75s", " task new with a direct get ... ");

    	$crawler = $this->client->request('GET', '/task/new');

    	$this->_commonTests($crawler, 'New task', 'new task');

    	print "done.\n";
    }

    public function testNewAjax() {

        printf("%-75s", " task new with ajax ... ");

        $crawler = $this->client->request('GET', '/task/new', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->_commonTests($crawler, 'New task', 'new task');

        print "done.\n";
    }

    public function testNewFromIndex() {

        printf("%-75s", " task new from task list ... ");

    	$crawler = $this->client->request('GET', '/task/');

    	$link = $crawler->filter('a:contains("new task")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	$this->_commonTests($landing, 'New task', 'new task');

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

        $crawler = $this->client->followRedirect();

        $this->_commonTests($crawler, 'Task details', 'task details');

        // check table content
        $this->assertTrue($crawler->filter('table:contains("test task")')->count() == 1);
        $this->assertTrue($crawler->filter('table:contains("deadline")')->count() == 1);
        $this->assertTrue($crawler->filter("table:contains(\"$date\")")->count() == 1);

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

        $this->_commonTests($crawler, 'New task', 'new task');

        // error message
        $this->assertTrue($crawler->filter('html:contains("This value should not be blank")')->count() == 1);

    	print "done.\n";
    }

    public function testEdit() {

        printf("%-75s", " task edit with a direct get ... ");

    	$crawler = $this->client->request('GET', '/task/1/edit');

    	$this->_commonTests($crawler, 'Edit task', 'edit task');

    	print "done.\n";
    }

    public function testEditAjax() {

        printf("%-75s", " task edit with ajax ... ");

        $crawler = $this->client->request('GET', '/task/1/edit', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->_commonTests($crawler, 'Edit task', 'edit task');

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

        $crawler = $this->client->followRedirect();

        $this->_commonTests($crawler, 'Task details', 'task details');

        // check table content
        $this->assertTrue($crawler->filter('table:contains("test task updated")')->count() == 1);
        $this->assertTrue($crawler->filter('table:contains("deadline")')->count() == 1);
        $this->assertTrue($crawler->filter("table:contains(\"$date\")")->count() == 1);

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

        $this->_commonTests($crawler, 'Edit task', 'edit task');

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
