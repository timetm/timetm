<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashboardControllerTest extends WebTestCase
{

	public function setUp() {

		$this->client = static::createClient(array(), array(
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => '1234',
		));
	}

    public function testIndex() {

        print " -- DASHBOARD ------------------------------------------------------------------\n\n.";
        printf("%-75s", " dashboard index with a direct get ... ");

        $crawler = $this->client->request('GET', '/');

        $this->assertTrue($crawler->filter('html:contains("Tomorrow")')->count() == 1);

		print "done.\n";
    }

    public function testIndexAjax() {

        printf("%-75s", " dashboard index with ajax ... ");

        $crawler = $this->client->request('GET', '/', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->assertTrue($crawler->filter('html:contains("Tomorrow")')->count() == 1);

        print "done.\n\n\n";
    }

}
