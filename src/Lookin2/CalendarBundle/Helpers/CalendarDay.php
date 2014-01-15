<?php

// src/Lookin2/CalendarBundle/Helpers/CalendarDay.php

/**
 * Class to generate day views
 *
 * @author andre@at-info.ch
 * @www.at-info.ch
 */
namespace Lookin2\CalendarBundle\Helpers;

use Symfony\Component\Routing\Router;

class CalendarDay extends Calendar {

	/**
	 * @var integer
	 */
	private $dayStart;

	/**
	 * @var integer
	 */
	private $dayEnd;

	/**
	 * Constructor.
	 *
	 * @param service $router      The router service
	 * @param integer $dayStart    Configuration parameter
	 * @param integer $dayEnd      Configuration parameter
	 */
	public function __construct(Router $router, $dayStart, $dayEnd) {
		parent::__construct($router);
		$this->type = "day";
		// TODO : parameters validation
		$this->dayStart = $dayStart;
		$this->dayEnd   = $dayEnd;
	}



	/**
	 * Set day
	 *
	 * @param string $year
	 */
	private function setDay($day) {
		// TODO : validation : check if integer, if in month
		if (!$day) {
			$day  = date('d');
		}
		$this->day = $day;
	}

	private function setDayName() {
		$this->dayName = date('D', mktime(0, 0, 0, $this->month, $this->day, $this->year));;
	}

	/**
	 * Set PrevMonthDay
	 */
	private function setPrevMonthDay() {

		$daysInLastMonth =  date('t', mktime(0, 0, 0, $this->month - 1, 1, $this->year));
		if ( $this->day > $daysInLastMonth ) {
			$this->PrevMonthDay = $daysInLastMonth;
		}
		else {
			$this->PrevMonthDay = $this->day;
		}
	}


	public function ChildInit($day) {
		// set common vars
		$this->setDay($day);
		$this->setDayName();
		$this->setPrevMonthDay();
		$this->setNextMonthDay();
	}

	
	/**
	 * Set additionnal panel navigation parameters
	 */
	public function setAdditionnalNavigationParameters() {
		$this->yesterdayDay   = date('d', mktime(0, 0, 0, $this->month, $this->day - 1, $this->year));
		$this->yesterdayMonth = date('m', mktime(0, 0, 0, $this->month, $this->day - 1, $this->year));
		$this->yesterdayYear  = date('Y', mktime(0, 0, 0, $this->month, $this->day - 1, $this->year));
		$this->tomorrowDay    = date('d', mktime(0, 0, 0, $this->month, $this->day + 1, $this->year));
		$this->tomorrowMonth  = date('m', mktime(0, 0, 0, $this->month, $this->day + 1, $this->year));
		$this->tomorrowYear   = date('Y', mktime(0, 0, 0, $this->month, $this->day + 1, $this->year));
	}
	
	/**
	 * Set NextMonthDay
	 */
	private function setNextMonthDay() {
	
		$daysInNextMonth =  date('t', mktime(0, 0, 0, $this->month + 1, 1, $this->year));
	
		if ( $this->day > $daysInNextMonth ) {
			$this->NextMonthDay = $daysInNextMonth;
		}
		else {
			$this->NextMonthDay = $this->day;
		}
	}

	
	/**
	 * Get YesterdayUrl
	 *
	 * @return string
	 */
	public function getYesterdayUrl() {
		$url = $this->router->generate('day', array(
			'year'  => $this->yesterdayYear,
			'month' => $this->yesterdayMonth ,
			'day'   => $this->yesterdayDay
		));
		return $url;
	}

	
	/**
	 * Get YesterdayUrl
	 *
	 * @return string
	 */
	public function getTomorrowUrl() {
		$url = $this->router->generate('day', array(
				'year'  => $this->tomorrowYear,
				'month' => $this->tomorrowMonth ,
				'day'   => $this->tomorrowDay
		));
		return $url;
	}
	

	/**
	 * Get day 
	 *
	 * @return string
	 */
	public function getDay() {
		return $this->day;
	}

	/**
	 * Get dayName
	 *
	 * @return string
	 */
	public function getDayName() {
		return $this->dayName;
	}

	/**
	 * Get day stamp
	 *
	 * @return string
	 */
	public function getCurrentDayStamp() {
		return $this->dayName . ', ' . (int)$this->day . ' ' . $this->monthName . ' ' . $this->year;
	}


	/**
	 * get the hours to display for a day view
	 * 
	 * @return array $dayTimes    A list of day times by step
	 */
	public function getDayTimes() {
	
		$step = 60;

		$dayTimes = array();
	
		for ( $hour = $this->dayStart; $hour <= $this->dayEnd; $hour++ ) {
			for ( $minsStep = 0; $minsStep < 60; $minsStep += $step ) {
				$minsStep = ( $minsStep < 10 ) ? '0'.$minsStep : $minsStep;
				$time = $hour . 'h' . $minsStep;
				array_push($dayTimes, $time);
			}
		}
	
		return $dayTimes;
	}
	
}
