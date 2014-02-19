<?php
/**
 * This file is part of Lookin2
 *
 * @author André andre@at-info.ch
 */

// src/Lookin2/CalendarBundle/Model/CalendarWeek.php

namespace Lookin2\CalendarBundle\Model;

use Symfony\Component\Routing\Router;

/**
 * class representing a weekly calendar
 */
class CalendarWeek extends Calendar {

	/**
	 * the router service
	 *
	 * @var     \Symfony\Component\Routing\Router
	 */
	protected $router;


	/**
	 * Constructor.
	 *
	 * @param   service   $router   The router service
	 */
	public function __construct(Router $router) {
		parent::__construct($router);
	}

	/**
	 * Set weekno
	 *
	 * @param   string    $weekno
	 */
	private function setWeekno($weekno) {
		// TODO : validation : check if integer, if in month
		if (!$weekno) {
			$weekno  = date('W');
		}
		$this->weekno = $weekno;
	}

	/**
	 * Get weekno
	 *
	 * @return string
	 */
	public function getWeekno() {
		return $this->weekno;
	}

	
	/**
	 * Set month
	 *
	 *
	 */
	protected function setWeekMonth() {
	
		$weekMonthes = array();
	
		for ( $i = 1; $i < 8; $i++ ) {
			array_push($weekMonthes, date('m', strtotime($this->year . '-W' . $this->weekno . '-' . $i )));
		}

		$buffer = array_count_values($weekMonthes);

		$currentCount = 0;
		$currentMonth = null;
		foreach ( $buffer as $month => $count ) {
			
			if ( $count > $currentCount ) {
				$currentCount = $count;
				$currentMonth = $month;
			}
		}

		$this->month = $currentMonth;

	}


	/**
	 * initialize the calendar.
	 *
	 * set :
	 *
	 * - month
	 * - monthName
	 *
	 * extends Calender::init
	 * @see Calender::init()        The extended function
	 *
	 * @param   mixed     $param
	 */
	public function childInit(array $options = array()) {
		
		// set common vars
		$this->setYear($options['year']);
		$this->setWeekno($options['weekno']);
		$this->setWeekMonth();
		
	}


	/**
	 * Set additionnal panel navigation parameters
	 */
	public function setAdditionnalNavigationParameters() {
		// dummy;
	}


	/**
	 * get the dates to display for a weekly view
	 *
	 * @return  array     $weekDates    A list of dates
	 *
	 */
	public function getWeekCalendarDates() {

		$weekDates = array();

		for ( $i = 1; $i < 8; $i++ ) {
			array_push($weekDates, date('Y-m-d', strtotime($this->year . '-W' . $this->weekno . '-' . $i )));
		}

		return $weekDates;
	}


}
