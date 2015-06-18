<?php

/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

// src/TimeTM/CoreBundle\Calendar/Model/Calendar.php
namespace TimeTM\CoreBundle\Model;

use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Abstract class representing a calendar
 *
 * @abstract
 *
 */
abstract class Calendar {
	
	/**
	 * the router service
	 *
	 * @var \Symfony\Component\Routing\Router
	 */
	protected $router;
	
	/**
	 * the translator service
	 *
	 * @var \Symfony\Component\Translation\Translator
	 */
	protected $translator;
	
	/**
	 * the current year
	 *
	 * @var string $year
	 */
	private $year;
	
	/**
	 * the current month
	 *
	 * @var string $month
	 */
	private $month;
	
	/**
	 * monthName
	 *
	 * @var string
	 */
	private $monthName;
	
	/**
	 * the week number
	 *
	 * @var string
	 */
	private $weekno;

	/**
	 * prevMonthYear
	 *
	 * @var string
	 */
	private $prevMonthYear;
	
	/**
	 * prevMonthMonth
	 *
	 * @var string
	 */
	private $prevMonthMonth;
	
	/**
	 * nextMonthMonth
	 *
	 * @var string
	 */
	private $nextMonthMonth;
	
	/**
	 * nextMonthYear
	 *
	 * @var string
	 */
	private $nextMonthYear;
	
	
	/*
	 * -- public ----------------------------------------------------------------
	 */
	
	/**
	 * Constructor.
	 *
	 * @param service $router
	 *        	The router service
	 */
	public function __construct(Router $router, TranslatorInterface $translator) {
		$this->router = $router;
		$this->translator = $translator;
	}
	
	/**
	 * initialize the calendar.
	 *
	 * set :
	 *
	 * - year
	 *
	 * exec :
	 *
	 * - childInit()
	 * - setPanelNavigationParameters()
	 *
	 * @param array $options        	
	 */
	public function init(array $options = array()) {
		$this->childInit( $options );
		$this->setMonthName();
		// set parameters for url generation
		$this->setPanelNavigationParameters();
	}
	
	/**
	 * Get year
	 *
	 * @return string
	 */
	public function getYear() {
		return $this->year;
	}
	
	/**
	 * Get monthName
	 *
	 * @return string
	 */
	public function getMonthName() {
		return $this->translator->trans( $this->monthName );
	}
	
	/**
	 * Get PrevYearUrl
	 *
	 * @param string $mode
	 *        	Values : month, week, day
	 *        	
	 * @return string $url
	 */
	public function getYearUrl($mode, $direction) {

		$year = '';
		
		if ( $direction === 'next' ) {
			$year = $this->year + 1;
		}
		else {
			$year = $this->year - 1;
		}

		switch ($mode) {
			case 'day' :
				$url = $this->router->generate ( $mode, array(
					'year' => $year,
					'month' => $this->month,
					'day' => $this->getDay () 
				));
				break;
			case 'month' :
				$url = $this->router->generate ( $mode, array(
					'year' => $year,
					'month' => $this->month 
				));
				break;
			case 'week' :
				$url = $this->router->generate ( $mode, array(
					'year' => $year,
					'weekno' => $this->getWeekno () 
				));
				break;
		}
		return $url;
	}
	
	/**
	 * Get PrevMonthUrl
	 *
	 * @param string $mode
	 *        	Values : month, day
	 *        	
	 * @return string $url
	 */
	public function getPrevMonthUrl($mode) {
		switch ($mode) {
			case 'day' :
				$url = $this->router->generate ( $mode, array(
					'year' => $this->prevMonthYear,
					'month' => $this->prevMonthMonth,
					'day' => $this->getPrevMonthDay () 
				));
				break;
			case 'month' :
				$url = $this->router->generate ( $mode, array(
					'year' => $this->prevMonthYear,
					'month' => $this->prevMonthMonth 
				));
				break;
		}
		return $url;
	}
	
	/**
	 * Get NextMonthUrl
	 *
	 * @param string $mode
	 *        	Values : month, day
	 *        	
	 * @return string $url
	 */
	public function getNextMonthUrl($mode) {
		switch ($mode) {
			case 'day' :
				$url = $this->router->generate ( $mode, array(
					'year' => $this->nextMonthYear,
					'month' => $this->nextMonthMonth,
					'day' => $this->getNextMonthDay () 
				));
				break;
			case 'month' :
				$url = $this->router->generate ( $mode, array(
					'year' => $this->nextMonthYear,
					'month' => $this->nextMonthMonth 
				));
				break;
		}
		return $url;
	}

	/**
	 * Get DayUrl
	 *
	 * @param string $_day        	
	 *
	 * @return string $url
	 */
	public function getDayUrl($_day = null) {
		
		// if called without parameter
		if (empty ( $_day )) {
			// if we are in current month set day to today
			if (date ( 'm' ) == $this->getMonth () && date ( 'Y' ) == $this->getYear ()) {
				$_day = date ( 'd' );
			} else {
				$_day = '01'; // default
			}
		}
		
		$url = $this->router->generate ( 'day', array(
				'year' => $this->year,
				'month' => $this->month,
				'day' => $_day 
		) );
		
		return $url;
	}
	
	/**
	 * Get ModeChangeUrl
	 *
	 * @param string $view        	
	 *
	 * @return string $url
	 */
	public function getModeChangeUrl($view) {
		switch ($view) {
			case 'month' :
				$url = $this->router->generate ( 'month', array (
					'year' => $this->year,
					'month' => $this->month 
				) );
				break;
			case 'week' :
				$url = $this->router->generate ( 'week', array (
					'year' => $this->year,
					'weekno' => $this->getWeekno () 
				));
				break;
		}
		
		return $url;
	}
	
	/**
	 * get the dates to display for a monthly view
	 *
	 * @param string $view
	 *        	The view displayed : Month, Day, Week
	 *        	
	 * @return array $monthDates A list of dates
	 *        
	 */
	public function getMonthCalendarDates() {

		$dateStamp =  $this->year . '/' . $this->month . '/';
		

		$monthDates = array();

		$currentDayOfWeek = date( 'N', mktime ( 0, 0, 0, $this->month, 1, $this->year )) - 1;
		$daysInMonth = date( 't', mktime ( 0, 0, 0, $this->month, 1, $this->year ));
		$daysInLastMonth = date( 't', mktime ( 0, 0, 0, $this->month - 1, 1, $this->year ));

		// -- PREVIOUS MONTH --------------------------------------------------------
		$url = $this->getPrevMonthUrl( 'month' );
		for($x = 0; $x < $currentDayOfWeek; $x ++) {
			$dayNum = (($daysInLastMonth - ($currentDayOfWeek - 1)) + $x);
			array_push( $monthDates, array (
				'day' => $dayNum,
				'url' => $url ,
			));
		}

		// -- CURRENT MONTH ---------------------------------------------------------
		for($dayNum = 1; $dayNum <= $daysInMonth; $dayNum ++) {
			$dayLink = ($dayNum > 9) ? $dayNum : '0' . $dayNum;
			array_push( $monthDates, array (
				'day' => $dayNum,
				'url' => $this->getDayUrl($dayLink),
				'datestamp' => $dateStamp . $dayLink
			));
			$currentDayOfWeek ++;
			if ($currentDayOfWeek == 7) {
				$currentDayOfWeek = 0;
			}
		}

		// -- NEXT MONTH ------------------------------------------------------------
		$url = $this->getNextMonthUrl( 'month' );
		if ($currentDayOfWeek < 7 && $currentDayOfWeek != 0) {
			for($dayNum = 1; $dayNum <= (7 - $currentDayOfWeek); $dayNum ++) {
// 				$dayLink = ($dayNum < 10) ? '0' . $dayNum : $dayNum;
				array_push( $monthDates, array (
					'day' => $dayNum,
					'url' => $url 
				));
			}
		}

		return $monthDates;
	}
	
	/*
	 * -- protected -------------------------------------------------------------
	 */
	
	/**
	 * Set year
	 *
	 * @param string $year        	
	 */
	protected function setYear($year) {
		if (! $year) {
			$year = date('Y');
		}
		$this->year = $year;
	}
	
	/**
	 * Set month
	 *
	 * @param string $month        	
	 */
	protected function setMonth($month) {
		if (! $month || $month < 1 || $month > 12) {
			$month = date('m');
		}
		$this->month = $month;
	}
	
	/**
	 * Set monthName
	 *
	 * @param string $month        	
	 */
	protected function setMonthName() {
		$this->monthName = date("F", mktime ( 0, 0, 0, $this->month ));
	}
	
	/**
	 * Set weekno
	 *
	 * @param string $weekno        	
	 */
	protected function setWeekno($weekno) {
		// TODO : validation : check if integer, if in month
		if (! $weekno) {
			$weekno = date('W');
		}
		$this->weekno = $weekno;
	}
	
	/**
	 * Get weekno
	 *
	 * @return string
	 */
	protected function getWeekno() {
		return $this->weekno;
	}
	
	/**
	 * Set additionnal panel navigation parameters.
	 *
	 * Hook function to extend setPanelNavigationParameters()
	 *
	 * @abstract
	 *
	 */
	abstract protected function setAdditionnalNavigationParameters();
	
	/**
	 * additionnal init.
	 *
	 * Hook function to extend init()
	 *
	 * @param string $param
	 *        	An additional parameter : $day
	 *        	
	 * @abstract
	 *
	 */
	abstract protected function childInit(array $options = array());
	
	/**
	 * Get month
	 *
	 * @return string
	 */
	public function getMonth() {
		return $this->month;
	}
	
	/*
	 * -- private ---------------------------------------------------------------
	 */
	
	/**
	 * Set panel navigation parameters.
	 *
	 * add the following properties
	 *
	 * - prevMonthYear.
	 * - prevMonthMonth.
	 * - nextMonthMonth.
	 * - nextMonthYear.
	 */
	private function setPanelNavigationParameters() {
		$this->prevMonthYear = date( 'Y', mktime ( 0, 0, 0, $this->month - 1, 1, $this->year ));
		$this->prevMonthMonth = date( 'm', mktime ( 0, 0, 0, $this->month - 1, 1, $this->year ));
		$this->nextMonthMonth = date( 'm', mktime ( 0, 0, 0, $this->month + 1, 1, $this->year ));
		$this->nextMonthYear = date( 'Y', mktime ( 0, 0, 0, $this->month + 1, 1, $this->year ));
		$this->setAdditionnalNavigationParameters ();
	}
}
