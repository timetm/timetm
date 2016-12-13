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

        print " -- CALENDAR -------------------------------------------------------------------\n\n.";
        printf("%-75s", " calendar month with a direct get and no parameters ... ");

        $testString = date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', '/month/');

        $this->_commonTests($crawler, $testString);

        print "done.\n";
    }


    public function testMonth() {

        $params = date('Y') . '/' . date('m');

        printf("%-75s", " calendar month with a direct get and parameters $params ... ");

        $testString = date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', "/month/$params");

        $this->_commonTests($crawler, $testString);

        print "done.\n";
    }

    public function testMonthNoParamsFromMainNav() {

        printf("%-75s", " calendar month from main navigation ... ");

    	$testString = date('F') . " " . date('Y');

    	$crawler = $this->client->request('GET', '/');

    	$link = $crawler->filter('a:contains("calendar")')->eq(0)->link();

    	$landing = $this->client->click($link);

    	$this->_commonTests($landing, $testString);

        print "done.\n";
    }

    public function testMonthNoParamsAjax() {

        printf("%-75s", " calendar month with ajax and no parameters ... ");

        $testString = date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', '/month/', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->_commonTests($crawler, $testString);

        print "done.\n";
    }

    public function testMonthAjax() {

        $params = date('Y') . '/' . date('m');

        printf("%-75s", " calendar month with ajax parameters $params ... ");

        $testString = date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', "/month/$params", array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->_commonTests($crawler, $testString);

        print "done.\n";
    }


    public function testMonthWrongDate() {

        $params = date('Y') . '/' . 13;

    	printf("%-75s", " calendar month with a direct get and WRONG date $params ... ");

        $crawler = $this->client->request('GET', "/month/$params");

        $this->assertEquals(404 , $this->client->getResponse()->getStatusCode());

        print "done.\n";
    }


    public function testMonthWrongParams() {

        $params = date('Y') . '/' . '1g';

        printf("%-75s", " calendar month with a direct get and WRONG parameters $params ... ");

        $crawler = $this->client->request('GET', "/month/$params");

        $this->assertEquals(404 , $this->client->getResponse()->getStatusCode());

        print "done.\n\n";
    }


    /*
     *  week ------------------------------------------------------------------
     */
    public function testWeekNoParams() {

        printf("%-75s", " calendar week with a direct get and no paramters ... ");

        $testString = 'Week' . " " . date('W');

        $crawler = $this->client->request('GET', '/week/');

        $this->_commonTests($crawler, $testString);

        print "done.\n";
    }

    public function testWeek() {

        $params = date('Y') . '/' . date('W');

        printf("%-75s", " calendar week with a direct get and parameters $params ... ");

        $testString = 'Week' . " " . date('W');

        $crawler = $this->client->request('GET', "/week/$params");

        $this->_commonTests($crawler, $testString);

        print "done.\n";
    }

    public function testWeekNoParamsFromPanelNav() {

        printf("%-75s", " calendar week from panel navigation ... ");

        $testString = 'Week' . " " . date('W');

        $crawler = $this->client->request('GET', '/');

        $link = $crawler->filter('a:contains("Week")')->eq(0)->link();

        $landing = $this->client->click($link);

        $this->_commonTests($landing, $testString);

        print "done.\n";
    }

    public function testWeekNoParamsAjax() {

        printf("%-75s", " calendar week with ajax and no paramters ... ");

        $testString = 'Week' . " " . date('W');

        $crawler = $this->client->request('GET', '/week/', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->_commonTests($crawler, $testString);

        print "done.\n";
    }

    public function testWeekAjax() {

        $params = date('Y') . '/' . date('W');

        printf("%-75s", " calendar week with ajax and parameters $params ... ");

        $testString = 'Week' . " " . date('W');

        $crawler = $this->client->request('GET', "/week/$params", array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->_commonTests($crawler, $testString);

        print "done.\n";
    }

    public function testWeekWrongDate() {

        $params = date('Y') . '/' . '55';

        printf("%-75s", " calendar week with a direct get and WRONG date $params ... ");

        $crawler = $this->client->request('GET', "/week/$params");

        $this->assertEquals(404 , $this->client->getResponse()->getStatusCode());

        print "done.\n";
    }

    public function testWeekWrongParams() {

        $params = date('Y') . '/' . '5r';

        printf("%-75s", " calendar week with a direct get and WRONG parameters $params ... ");

        $crawler = $this->client->request('GET', "/week/$params");

        $this->assertEquals(404 , $this->client->getResponse()->getStatusCode());

        print "done.\n\n";
    }


    /*
     *  day -------------------------------------------------------------------
     */
    public function testDayNoParams() {

        printf("%-75s", " calendar day with a direct get and no parameters ... ");

        $testString = date('D') . ", " . date('j') . " " . date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', '/day/');

        $this->_commonTests($crawler, $testString);

        print "done.\n";
    }

    public function testDay() {

        $params = date('Y') . '/' . date('m') . '/' . date('d');

        printf("%-75s", " calendar day with a direct get and parameters $params ... ");

        $testString = date('D') . ", " . date('j') . " " . date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', "/day/$params");

        $this->_commonTests($crawler, $testString);

        print "done.\n";
    }

    public function testDayNoParamsFromPanelNav() {

        printf("%-75s", " calendar day from panel navigation ... ");

        $testString = date('D') . ", " . date('j') . " " . date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', '/');

        $link = $crawler->filter('a:contains("Day")')->eq(0)->link();

        $landing = $this->client->click($link);

        $this->_commonTests($landing, $testString);

        print "done.\n";
    }

    public function testDayNoParamsAjax() {

        printf("%-75s", " calendar day with ajax and no parameters ... ");

        $testString = date('D') . ", " . date('j') . " " . date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', '/day/', array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        print "done.\n";

        $this->_commonTests($crawler, $testString);
    }

    public function testDayAjax() {

        $params = date('Y') . '/' . date('m') . '/' . date('d');

        printf("%-75s", " calendar day with ajax and parameters $params ... ");

        $testString = date('D') . ", " . date('j') . " " . date('F') . " " . date('Y');

        $crawler = $this->client->request('GET', "/day/$params", array(), array(), array(
            'X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->_commonTests($crawler, $testString);

        print "done.\n";
    }

    public function testDayWrongDate() {

        $params = date('Y') . '/' . '13' . '/' . date('d');

        printf("%-75s", " calendar day with a direct get and parameters $params ... ");

        $crawler = $this->client->request('GET', "/day/$params");

        $this->assertEquals(404 , $this->client->getResponse()->getStatusCode());

        print "done.\n";
    }

    public function testDayWrongParams() {

        $params = '201h' . '/' . date('m') . '/' . date('d');

        printf("%-75s", " calendar day with a direct get and parameters $params ... ");

        $crawler = $this->client->request('GET', "/day/$params");

        $this->assertEquals(404 , $this->client->getResponse()->getStatusCode());

        print "done.\n\n\n";
    }


    private function _commonTests($crawler, $testString) {

        // test title
        $this->assertTrue($crawler->filter("title:contains(\"$testString\")")->count() == 1);

        // panel
        $this->assertTrue($crawler->filter("#dateDisplay:contains(\"$testString\")")->count() == 1);
    }
}
