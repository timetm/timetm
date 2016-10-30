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







}
