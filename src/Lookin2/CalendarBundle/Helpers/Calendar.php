<?php

// src/Lookin2/CalendarBundle/Helpers/Calendar.php

/**
 * Class to generate month, week and day views
 *
 * @author andre@at-info.ch
 * @www.at-info.ch
 */
namespace Lookin2\CalendarBundle\Helpers;

use Symfony\Component\Routing\Router;

class Calendar {

	// -- inject the router
	protected $router;

	public function __construct(Router $router) {
		$this->router = $router;
	}

	// -- set Year
	public function setYear($year) {
		if (!$year) { $year  = date('Y'); }
		$this->Year = $year;
	}

	// -- get Year
	public function getYear() {
		return $this->Year;
	}

	// -- set Month
	public function setMonth($month) {
		if ( ! $month or $month < 1 or $month > 12 ) { $month = date('m'); }
		$this->Month = $month;
		$this->setPrevMonthYear();
		$this->setPrevMonthMonth();
		$this->setNextMonthMonth();
		$this->setNextMonthYear();
	}

	// get Month
	// 	public function getMonth() {
	// 		return $this->Month;
	// 	}

	// -- get current month stamp
	public function getCurrentMonthStamp() {
		return (int) $this->Month . ' ' . $this->Year;;
	}

	// -- create next/prev month and year for url parameters 
	public function setPrevMonthYear() {
		$this->PrevMonthYear = date('Y', mktime(0, 0, 0, $this->Month - 1, 1, $this->Year));
	}

	public function setPrevMonthMonth() {
		$this->PrevMonthMonth = date('m', mktime(0, 0, 0, $this->Month - 1, 1, $this->Year));
	}

	public function setNextMonthMonth() {
		$this->NextMonthMonth = date('m', mktime(0, 0, 0, $this->Month + 1, 1, $this->Year));
	}

	public function setNextMonthYear() {
		$this->NextMonthYear = date('Y', mktime(0, 0, 0, $this->Month + 1, 1, $this->Year));
	}

	// -- create previous year url
	public function getPrevYearUrl() {
		$url = $this->router->generate('month', array(
				'year'  => $this->Year - 1 ,
				'month' => $this->Month )
		);
		return $url;
	}

	// -- create previous month url
	public function getPrevMonthUrl() {
		$url = $this->router->generate('month', array(
  		'year'  => $this->PrevMonthYear ,
			'month' => $this->PrevMonthMonth
		));
		return $url;
	}

	// -- create next month url
	public function getNextMonthUrl() {
		$url = $this->router->generate('month', array(
				'year'  => $this->NextMonthYear ,
				'month' => $this->NextMonthMonth
		));
		return $url;
	}
	
	// -- create next year url
	public function getNextYearUrl() {
		$url = $this->router->generate('month', array(
				'year'  => $this->Year + 1 ,
				'month' => $this->Month )
		);
		return $url;
	}

	// not really used now
	public function getDayUrl($day) {
		$url = $this->router->generate('day', array(
				'year'  => $this->Year ,
				'month' => $this->Month ,
				'day'   => $day
		));
		return $url;
	}
	

	/**
	 * get the dates to display for a monthly view
	 */
	public function getMonthCalendarDates() {
		
		$monthDates = array();
	
		$currentDayOfWeek = date('N', mktime(0, 0, 0, $this->Month,     1, $this->Year)) - 1;
		$daysInMonth =      date('t', mktime(0, 0, 0, $this->Month,     1, $this->Year));
		$daysInLastMonth =  date('t', mktime(0, 0, 0, $this->Month - 1, 1, $this->Year));
	
		// -- PREVIOUS MONTH --------------------------------------------------------
		for($x = 0; $x < $currentDayOfWeek; $x++) {
			$dayNum = (($daysInLastMonth - ($currentDayOfWeek - 1)) + $x);
			array_push($monthDates, array ( 
				'day'     => $dayNum,
// 				'dayLink' => $dayNum,
// 				'month'   => $this->PrevMonthMonth, 
// 				'year'    => $this->PrevMonthYear,
				'id'      => $this->getPrevMonthUrl()
			));
		};
	
		// -- CURRENT MONTH ---------------------------------------------------------
		for($dayNum = 1; $dayNum <= $daysInMonth; $dayNum++) {
			$dayLink = ( $dayNum > 9 ) ? $dayNum : '0'.$dayNum;
			array_push($monthDates, array ( 
				'day'     => $dayNum, 
// 				'dayLink' => $dayLink, 
// 				'month'   => $this->Month, 
// 				'year'    => $this->Year,
				'id'      => $this->getDayUrl($dayLink),
				''
			));
			$currentDayOfWeek++;
			if($currentDayOfWeek == 7) {
				$currentDayOfWeek = 0;
			};
		};
	
		// -- NEXT MONTH ------------------------------------------------------------
		if ($currentDayOfWeek < 7 && $currentDayOfWeek != 0) {
			for ($dayNum = 1; $dayNum <= (7 - $currentDayOfWeek); $dayNum++) {
				$dayLink = ( $dayNum < 10 ) ? '0'.$dayNum : $dayNum;
				array_push($monthDates, array ( 
					'day'     => $dayNum, 
// 					'dayLink' => $dayLink,
// 					'month'   => $this->NextMonthMonth, 
// 					'year'    => $this->NextMonthYear,
					'id'      => $this->getNextMonthUrl()
				));
			};
		};
	
		return $monthDates;
	}
}
