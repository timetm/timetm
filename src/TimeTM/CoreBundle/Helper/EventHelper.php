<?php 

/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\CoreBundle\Helper;

use TimeTM\CoreBundle\Entity\Event;

/**
 * Helper class for event
 *
 * @author André Friedli <a@frian.org>
 */
class EventHelper {

	/**
	 * Fill event for form
	 * 
	 * @param      string    $year
	 * @param      string    $month
	 * @param      string    $day
	 * @param      string    $hour
	 * @param      string    $min
	 * 
	 * @return     \TimeTM\CoreBundle\Entity\Event
	 */
	public function fillNewEvent($year = null, $month = null, $day = null, $hour = null, $min = null) {

		$event = new Event();

		if ($year) {
			$startDate = date("Y-m-d", mktime( date("H") + 1 , 0, 0, $month , $day , $year));
			$endDate = date("Y-m-d", mktime( date("H") + 2 , 0, 0, $month , $day , $year));
		}
		else {
			$startDate = date("Y-m-d", mktime( date("H") + 1 , 0, 0, date("n") , date("j") , date("Y")));
			$endDate = date("Y-m-d", mktime( date("H") + 2 , 0, 0, date("n") , date("j") , date("Y")));
		}

		if ($hour) {
			$startTime = date("H:i", mktime( $hour , 0, 0, date("n") , date("j") , date("Y")));
			$endTime = date("H:i", mktime( $hour + 1 , 0, 0, date("n") , date("j") , date("Y")));
		}
		else {
			$startTime = date("H:i", mktime( date("H") + 1 , $min, 0, date("n") , date("j") , date("Y")));
			$endTime = date("H:i", mktime( date("H") + 2 , $min, 0, date("n") , date("j") , date("Y")));
		}

		$event->setStartDate(new \DateTime($startDate));
		$event->setStartTime(new \DateTime($startTime));
		$event->setEndDate(new \DateTime($endDate));
		$event->setEndTime(new \DateTime($endTime));

		return $event;
	}
}
