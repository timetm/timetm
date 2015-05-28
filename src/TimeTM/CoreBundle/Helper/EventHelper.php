<?php 

namespace TimeTM\CoreBundle\Helper;

use TimeTM\CoreBundle\Entity\Event;

class EventHelper {

	public function fillNewEvent($year = null, $month = null, $day = null) {

		$event = new Event();
		
		if ($year) {
			$startDate = date( "Y-m-d",  mktime( date("H") + 1 , 0, 0, $month , $day , $year ) );
			$endDate = date( "Y-m-d",  mktime( date("H") + 2 , 0, 0, $month , $day , $year  ) );
		}
		else {
			$startDate = date( "Y-m-d",  mktime( date("H") + 1 , 0, 0, date("n") , date("j") , date("Y") ));
			$endDate = date( "Y-m-d",  mktime( date("H") + 2 , 0, 0, date("n") , date("j") , date("Y") ));
		}
		
		$startTime = date( "H:i",  mktime( date("H") + 1 , 0, 0, date("n") , date("j") , date("Y") ));
		$endTime = date( "H:i",  mktime( date("H") + 2 , 0, 0, date("n") , date("j") , date("Y") ));
		
		$event->setStartDate(new \DateTime($startDate));
		$event->setStartTime(new \DateTime($startTime));
		$event->setEndDate(new \DateTime($endDate));
		$event->setEndTime(new \DateTime($endTime));
		
		return $event;
	}

	
}
