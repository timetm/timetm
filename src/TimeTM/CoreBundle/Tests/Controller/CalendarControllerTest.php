<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalendarControllerTest extends WebTestCase
{

	public function setUp()
	{
		$this->client = static::createClient(array(), array(
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => '1234',
		));
	}

    public function testMonthNoParams()
    {
    	print " testing calendar month with a direct get ... ";

        $monthName = date('F');
        $currentYear = date('Y');
        $testString = $monthName . " " . $currentYear;

        $crawler = $this->client->request('GET', '/month/');

        print "done.\n";

        $this->assertTrue($crawler->filter("html:contains(\"$testString\")")->count() == 1);
    }

    public function testMonthNoParamsFromMainNav()
    {
    	print " testing calendar month from main navigation ... ";

    	$monthName = date('F');
    	$currentYear = date('Y');
    	$testString = $monthName . " " . $currentYear;

    	$crawler = $this->client->request('GET', '/');

    	$link = $crawler->filter('a:contains("calendar")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	print "done.\n\n";

    	$this->assertTrue($landing->filter("html:contains(\"$testString\")")->count() == 1);
    }
}
