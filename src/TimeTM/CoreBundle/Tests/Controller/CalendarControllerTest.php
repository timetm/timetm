<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalendarControllerTest extends WebTestCase
{
    public function testMonthNoParams()
    {
        $client = static::createClient();

        $monthName = date('F');
        $currentYear = date('Y');
        $testString = $monthName . " " . $currentYear;

        $crawler = $client->request('GET', '/month/');

        $this->assertTrue($crawler->filter("html:contains(\"$testString\")")->count() == 1);
    }
}
