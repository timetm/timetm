<?php

/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\CoreBundle\Model;

/**
 * Class representing day times
 * 
 * @author André Friedli <a@frian.org>
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
				$timestamp = $hour . ':' . $minsStep;
				$hour = ($hour < 10) ? '0' . $hour : $hour;
				$url = $hour . '/' . $minsStep;
				array_push ($dayTimes, array(
					// hour HH
					'hour' => $hour,
					// HHhMM
					'time' => $time,
					// HH:MM
					'timestamp' => $timestamp,
					// HH/MM
					'url' => $url)
				);
			}
		}

		return $dayTimes;
	}
}
