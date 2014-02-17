<?php
/**
 * This file is part of Lookin2
 *
 * @author André andre@at-info.ch
 */

// src/Lookin2/CalendarBundle/Model/Times.php

namespace Lookin2\CalendarBundle\Model;


/**
 * class representing a weekly calendar
 */
class Times {

	/**
	 * Constructor.
	 *
	 * @param   integer   $dayStart      Configuration parameter
	 * @param   integer   $dayEnd        Configuration parameter
	 */
	public function __construct($dayStart, $dayEnd) {
		// TODO : parameters validation
		$this->dayStart = $dayStart;
		$this->dayEnd   = $dayEnd;
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