<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashboardControllerTest extends WebTestCase {

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

        $this->_commonTests($crawler);

		print "done.\n";
    }

    public function testIndexAjax() {

        printf("%-75s", " dashboard index with ajax ... ");

        $crawler = $this->client->request('GET', '/', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->_commonTests($crawler);

        print "done.\n\n\n";
    }


    private function _commonTests($crawler) {

        // title
        $this->assertTrue($crawler->filter('title:contains("Dashboard")')->count() == 1);

        // content
        $this->assertTrue($crawler->filter('html:contains("Tomorrow")')->count() == 1);

        // panel
        $dateDisplay = date("F") . " " . date("Y");
        $this->assertTrue($crawler->filter("#dateDisplay:contains(\"$dateDisplay\")")->count() == 1);
    }

}
