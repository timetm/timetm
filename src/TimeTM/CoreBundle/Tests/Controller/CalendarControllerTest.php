<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalendarControllerTest extends WebTestCase
{

	public function setUp() {

		$this->client = static::createClient(array(), array(
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => '1234',
		));
	}


    /*
     *  month  ----------------------------------------------------------------
     */
    public function testMonthNoParams() {

    	print " testing calendar month with a direct get and no parameters ... ";

        $testString = date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', '/month/');

        print "done.\n";

        $this->assertTrue($crawler->filter("html:contains(\"$testString\")")->count() == 1);
    }


    public function testMonth() {

        $params = date('Y') . '/' . date('m');

    	print " testing calendar month with a direct get and parameters $params ... ";

        $testString = date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', "/month/$params");

        print "done.\n";

        $this->assertTrue($crawler->filter("html:contains(\"$testString\")")->count() == 1);
    }


    public function testMonthNoParamsFromMainNav() {

    	print " testing calendar month from main navigation ... ";

    	$testString = date('F') . " " . date('Y');

    	$crawler = $this->client->request('GET', '/');

    	$link = $crawler->filter('a:contains("calendar")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	print "done.\n";

    	$this->assertTrue($landing->filter("html:contains(\"$testString\")")->count() == 1);
    }

    public function testMonthNoParamsAjax() {

        print " testing calendar month with ajax and no parameters ... ";

        $testString = date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', '/month/', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        print "done.\n";

        $this->assertTrue($crawler->filter("html:contains(\"$testString\")")->count() == 1);
    }

    public function testMonthAjax() {

        $params = date('Y') . '/' . date('m');

    	print " testing calendar month with ajax parameters $params ... ";

        $testString = date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', "/month/$params", array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        print "done.\n\n";

        $this->assertTrue($crawler->filter("html:contains(\"$testString\")")->count() == 1);
    }

    /*
     *  week ------------------------------------------------------------------
     */
    public function testWeekNoParams() {

    	print " testing calendar week with a direct get and no paramters ... ";

        $testString = 'Week' . " " . date('W');

        $crawler = $this->client->request('GET', '/week/');

        print "done.\n";

        $this->assertTrue($crawler->filter("html:contains(\"$testString\")")->count() == 1);
    }

    public function testWeek() {

        $params = date('Y') . '/' . date('W');

    	print " testing calendar week with a direct get and parameters $params ... ";

        $testString = 'Week' . " " . date('W');

        $crawler = $this->client->request('GET', "/week/$params");

        print "done.\n";

        $this->assertTrue($crawler->filter("html:contains(\"$testString\")")->count() == 1);
    }

    public function testWeekNoParamsFromPanelNav() {

        print " testing calendar week from panel navigation ... ";

        $testString = 'Week' . " " . date('W');

        $crawler = $this->client->request('GET', '/');

        $link = $crawler->filter('a:contains("Week")')->eq(0)->link();

        $landing = $this->client->click($link);

        print "done.\n";

        $this->assertTrue($landing->filter("html:contains(\"$testString\")")->count() == 1);
    }

    public function testWeekNoParamsAjax() {

    	print " testing calendar week with ajax and no paramters ... ";

        $testString = 'Week' . " " . date('W');

        $crawler = $this->client->request('GET', '/week/', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        print "done.\n";

        $this->assertTrue($crawler->filter("html:contains(\"$testString\")")->count() == 1);
    }

    public function testWeekAjax() {

        $params = date('Y') . '/' . date('W');

    	print " testing calendar week with ajax and parameters $params ... ";

        $testString = 'Week' . " " . date('W');

        $crawler = $this->client->request('GET', "/week/$params", array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        print "done.\n\n";

        $this->assertTrue($crawler->filter("html:contains(\"$testString\")")->count() == 1);
    }


    /*
     *  day -------------------------------------------------------------------
     */
    public function testDayNoParams() {

    	print " testing calendar day with a direct get and no parameters ... ";

        $testString = date('D') . ", " . date('j') . " " . date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', '/day/');

        print "done.\n";

        $this->assertTrue($crawler->filter("html:contains(\"$testString\")")->count() == 1);
    }

    public function testDay() {

        $params = date('Y') . '/' . date('W') . '/' . date('d');

    	print " testing calendar week with a direct get and parameters $params ... ";

        $testString = date('D') . ", " . date('j') . " " . date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', "/day/$params");

        print "done.\n";

        $this->assertTrue($crawler->filter("html:contains(\"$testString\")")->count() == 1);
    }

    public function testDayNoParamsFromPanelNav() {

        print " testing calendar day from panel navigation ... ";

        $testString = date('D') . ", " . date('j') . " " . date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', '/');

        $link = $crawler->filter('a:contains("Day")')->eq(0)->link();

        $landing = $this->client->click($link);

        print "done.\n";

        $this->assertTrue($landing->filter("html:contains(\"$testString\")")->count() == 1);
    }

    public function testDayNoParamsAjax() {

    	print " testing calendar day with ajax and no parameters ... ";

        $testString = date('D') . ", " . date('j') . " " . date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', '/day/', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        print "done.\n";

        $this->assertTrue($crawler->filter("html:contains(\"$testString\")")->count() == 1);
    }

    public function testDayAjax() {

        $params = date('Y') . '/' . date('W') . '/' . date('d');

    	print " testing calendar week with ajax and parameters $params ... ";

        $testString = date('D') . ", " . date('j') . " " . date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', "/day/$params", array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        print "done.\n\n";

        $this->assertTrue($crawler->filter("html:contains(\"$testString\")")->count() == 1);
    }
}
