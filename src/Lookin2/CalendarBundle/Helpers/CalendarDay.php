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
		$this->dayStart = $dayStart;
		$this->dayEnd   = $dayEnd;
	}

	
	/**
	 * Get day stamp
	 *
	 * @return string
	 */
	public function getCurrentDayStamp() {
		return $this->monthName . ' ' . $this->year;;
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
