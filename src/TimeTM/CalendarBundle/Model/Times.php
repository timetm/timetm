<?php

/**
 * This file is part of TimeTM
 *
 * @author André andre@at-info.ch
 */

// src/TimeTM/CalendarBundle/Model/Times.php
namespace TimeTM\CalendarBundle\Model;

/**
 * class representing a weekly calendar
 */
class Times {
	
	/**
	 * dayStart
	 *
	 * @var string Start hour for a day or week view
	 */
	private $dayStart;
	
	/**
	 * dayEnd
	 *
	 * @var string End hour for a day or week view
	 */
	private $dayEnd;
	
	/**
	 * Constructor.
	 *
	 * @param integer $dayStart
	 *        	Configuration parameter
	 * @param integer $dayEnd
	 *        	Configuration parameter
	 */
	public function __construct($dayStart, $dayEnd) {
		// TODO : parameters validation
		$this->dayStart = $dayStart;
		$this->dayEnd = $dayEnd;
	}
	
	/**
	 * get the hours to display for a day view
	 *
	 * @return array $dayTimes A list of day times by step
	 */
	public function getDayTimes() {
		$step = 60;
		
		$dayTimes = array ();
		
		for($hour = $this->dayStart; $hour <= $this->dayEnd; $hour ++) {
			for($minsStep = 0; $minsStep < 60; $minsStep += $step) {
				$minsStep = ($minsStep < 10) ? '0' . $minsStep : $minsStep;
				$time = $hour . 'h' . $minsStep;
				array_push ( $dayTimes, $time );
			}
		}
		
		return $dayTimes;
	}
}