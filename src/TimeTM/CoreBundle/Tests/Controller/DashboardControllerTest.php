<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashboardControllerTest extends WebTestCase
{

	public function setUp()
	{
		$this->client = static::createClient(array(), array(
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => '1234',
		));
	}

    public function testIndex()
    {
    	print " testing dashboard index with a direct get ... ";

        $crawler = $this->client->request('GET', '/');

        $this->assertTrue($crawler->filter('html:contains("Tomorrow")')->count() == 1);

		print "done.\n\n";
    }
}
