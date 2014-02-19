<?php
/**
 * This file is part of Lookin2
 *
 * @author André andre@at-info.ch
 */

// src/Lookin2/CalendarBundle/Model/Calendar.php

namespace Lookin2\CalendarBundle\Model;

use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\Translator;

/**
 * Abstract class representing a calendar
 *
 * @abstract
 */
abstract class Calendar {

  /**
   * the router service
   * 
   * @var     \Symfony\Component\Routing\Router
   */
	protected $router;

	
	/**
	 * the translator service
	 *
	 * @var     \Symfony\Component\Translation\Translator
	 */
	protected $translator;


	/**
	 * the current year
	 * 
	 * @var     integer   $year
	 */
	protected $year;

	/**
	 * the current month
	 * 
	 * @var     integer   $month
	 */
	protected $month;


	/**
	 * Constructor.
	 *
	 * @param   service   $router   The router service
	 */
	public function __construct(Router $router, Translator $translator) {
		$this->router   = $router;
		$this->translator = $translator;
	}


  /**
   * Set year
   *
   * @param   string    $year
   */
	protected function setYear($year) {
		if (!$year) { $year  = date('Y'); }
		$this->year = $year;
	}


	/**
	 * Set month
	 *
	 * @param   string    $month
	 */
	protected function setMonth($month) {
		if ( ! $month or $month < 1 or $month > 12 ) {
			$month = date('m');
		}
		$this->month = $month;
	}

	
	/**
	 * Set monthName
	 *
	 * @param   string    $month
	 */
	protected function setMonthName() {
		$this->monthName =  date("F", mktime(0, 0, 0, $this->month));
	}


	/**
	 * Set additionnal panel navigation parameters.
	 * 
	 * Hook function to extend setPanelNavigationParameters()
	 * 
	 * @abstract
	 */
	abstract protected function setAdditionnalNavigationParameters();

	/**
	 * Set panel navigation parameters.
	 *
	 * add the following properties
	 * 
	 *  - prevMonthYear.
	 *  - prevMonthMonth.
	 *  - nextMonthMonth.
	 *  - nextMonthYear.
	 */
	private function setPanelNavigationParameters() {
		$this->prevMonthYear  = date('Y', mktime(0, 0, 0, $this->month - 1, 1, $this->year));
		$this->prevMonthMonth = date('m', mktime(0, 0, 0, $this->month - 1, 1, $this->year));
		$this->nextMonthMonth = date('m', mktime(0, 0, 0, $this->month + 1, 1, $this->year));
		$this->nextMonthYear  = date('Y', mktime(0, 0, 0, $this->month + 1, 1, $this->year));
		$this->setAdditionnalNavigationParameters();
	}

	/**
	 * additionnal init.
	 *
	 * Hook function to extend init()
	 *
	 * @param   string    $param    An additional parameter : $day
	 * 
	 * @abstract
	 */
	abstract protected function childInit(array $options = array());

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
	 * @param   array     $options
	 */
	public function init(array $options = array()) {

		$this->childInit($options);
		$this->setMonthName();
		// set parameters for url generation
		$this->setPanelNavigationParameters();
	}

	/**
	 * Get monthName
	 *
	 * @return  string
	 */
	public function getMonthName() {
		return $this->translator->trans($this->monthName); 
	}

  /**
   * Get month
   *
   * @return  string 
   */
	public function getMonth() {
		return $this->month;
	}
	
  /**
   * Get year
   *
   * @return  string 
   */
	public function getYear() {
		return $this->year;
	}

	
  /**
   * Get PrevYearUrl
   *
   * @param   string    $mode     Values : month, week, day
   *
   * @return  string    $url
   */
	public function getPrevYearUrl($mode) {
		switch ($mode) {
			case 'day':
				$url = $this->router->generate($mode, array(
					'year'  => $this->year - 1,
					'month' => $this->month ,
					'day'   => $this->day
				));
				break;
			case 'month':
				$url = $this->router->generate($mode, array(
					'year'  => $this->year - 1 ,
					'month' => $this->month 
				));
				break;
		}
		return $url;
	}

  /**
   * Get PrevMonthUrl
   *
   * @param   string    $mode     Values : month, week, day
   * 
   * @return  string    $url
   */
	public function getPrevMonthUrl($mode) {
		switch ($mode) {
			case 'day':
				$url = $this->router->generate($mode, array(
		  		'year'  => $this->prevMonthYear ,
					'month' => $this->prevMonthMonth, 
					'day'   => $this->prevMonthDay,
				));
				break;
			case 'month':
				$url = $this->router->generate($mode, array(
						'year'  => $this->prevMonthYear ,
						'month' => $this->prevMonthMonth
				));
				break;
		}
		return $url;
	}

  /**
   * Get NextMonthUrl
   *
   * @param   string    $mode     Values : month, week, day
   * 
   * @return  string    $url
   */
	public function getNextMonthUrl($mode) {
		switch ($mode) {
			case 'day':
				$url = $this->router->generate($mode, array(
						'year'  => $this->nextMonthYear ,
						'month' => $this->nextMonthMonth,
						'day'   => $this->nextMonthDay,
				));
				break;
			case 'month':
				$url = $this->router->generate($mode, array(
						'year'  => $this->nextMonthYear ,
						'month' => $this->nextMonthMonth
				));
				break;
		}
		return $url;
	}
	
  /**
   * Get NextYearUrl
   *
   * @param   string    $mode     Values : month, week, day
   * 
   * @return  string    $url
   */
	public function getNextYearUrl($mode) {
		switch ($mode) {
			case 'day':
				$url = $this->router->generate($mode, array(
					'year'  => $this->year + 1 ,
					'month' => $this->month,
					'day'   => $this->day
				));
				break;
			case 'month' :
				$url = $this->router->generate($mode, array(
						'year'  => $this->year + 1 ,
						'month' => $this->month
				));
				
				break;
		}
		return $url;
	}

  /**
   * Get DayUrl
   *
   * @param   string    $view     Values : month, week, day
   *
   * @param   string    $day
   *
   * @return  string    $url
   */
	public function getDayUrl($view, $day = null) {
		
		switch ($view) {
			case 'day':
				$url = $this->router->generate($view, array(
						'year'  => $this->year ,
						'month' => $this->month ,
						'day'   => $day
				));
				break;
			case 'month':
				$url = $this->router->generate('month', array(
						'year'  => $this->year ,
						'month' => $this->month ,
				));
				break;
		}

		return $url;
	}

	
	/**
	 * get the dates to display for a monthly view
	 * 
	 * @param   string    $view					 The view displayed : Month, Day, Week
	 * 
	 * @return  array     $monthDates    A list of dates
	 * 
	 * TODO : remove param $view ( not used )
	 */
	public function getMonthCalendarDates($view) {
		
		$monthDates = array();
	
		$currentDayOfWeek = date('N', mktime(0, 0, 0, $this->month,     1, $this->year)) - 1;
		$daysInMonth =      date('t', mktime(0, 0, 0, $this->month,     1, $this->year));
		$daysInLastMonth =  date('t', mktime(0, 0, 0, $this->month - 1, 1, $this->year));
	
		// -- PREVIOUS MONTH --------------------------------------------------------
		$url = $this->getPrevMonthUrl('month');
		for($x = 0; $x < $currentDayOfWeek; $x++) {
			$dayNum = (($daysInLastMonth - ($currentDayOfWeek - 1)) + $x);
			array_push($monthDates, array ( 
				'day' => $dayNum,
				'url' => $url
			));
		};
	
		// -- CURRENT MONTH ---------------------------------------------------------
		for($dayNum = 1; $dayNum <= $daysInMonth; $dayNum++) {
			$dayLink = ( $dayNum > 9 ) ? $dayNum : '0'.$dayNum;
			array_push($monthDates, array ( 
				'day' => $dayNum, 
				'url' => $this->getDayUrl($view,$dayLink),
			));
			$currentDayOfWeek++;
			if($currentDayOfWeek == 7) {
				$currentDayOfWeek = 0;
			};
		};
	
		// -- NEXT MONTH ------------------------------------------------------------
		$url = $this->getNextMonthUrl('month');
		if ($currentDayOfWeek < 7 && $currentDayOfWeek != 0) {
			for ($dayNum = 1; $dayNum <= (7 - $currentDayOfWeek); $dayNum++) {
				$dayLink = ( $dayNum < 10 ) ? '0'.$dayNum : $dayNum;
				array_push($monthDates, array ( 
					'day' => $dayNum, 
					'url' => $url
				));
			};
		};
	
		return $monthDates;
	}

}
