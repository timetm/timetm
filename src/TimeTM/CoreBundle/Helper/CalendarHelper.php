<?php
/**
 * This file is part of TimeTM
 *
 * @author André andre@at-info.ch
 */


namespace TimeTM\CoreBundle\Helper;

/**
 * class representing a weekly calendar
 */
class CalendarHelper {

	protected $em;
	
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		$this->em = $em;
	}

	/**
	 * add events to an array of dates
	 * 
	 * @param      TimeTM\CoreBundle\Model\Calendar   $calendar
	 * @param      array                              $dates
	 * 
	 * @return     array                              $dates
	 */
	public function addEventsToCalendar(\TimeTM\CoreBundle\Model\Calendar $calendar, array $dates, $type = 'month') {

		if ($type == 'month') {
			// get date for first and last day of month
			$firstDayOfMonth = date( 'Y-m-d', mktime( 0, 0, 0, $calendar->getMonth(), 1, $calendar->getYear() ) );
			$lastDayOfMonth  = date( 'Y-m-d', mktime( 0, 0, 0, $calendar->getMonth(), date( 't', mktime( 0, 0, 0, $calendar->getMonth(), 1, $calendar->getYear() ) ), $calendar->getYear() ) );
		}
		elseif ($type == 'week') {
			$firstDayOfMonth = $calendar->getFirstDateOfWeek('Y-m-d');
			$lastDayOfMonth = $calendar->getLastDateOfWeek('Y-m-d');
		}
		elseif ($type == 'day') {
			
		}

		// get query builder
		$queryBuilder = $this->em->createQueryBuilder();

		/*
		 * build and execute query
		 *
		 * select title,date
		 *   from Event e
		 *     join Agenda a on e.agenda_id =a.id
		 *     join fos_user u on a.user_id=u.id
		 *     where a.id=1; TODO
		*/
		$events = $queryBuilder
			->select('partial e.{id, title, place, startdate , starttime }')
			->from('TimeTMCoreBundle:Event', 'e')
			->leftjoin('e.agenda', 'a')
			->leftjoin('a.user', 'u')
			->where('e.startdate BETWEEN :firstDay AND :lastDay')
			->setParameter('firstDay', $firstDayOfMonth)
			->setParameter('lastDay', $lastDayOfMonth)
			->getQuery()
			->execute();

		// add events to the monthDates array
		foreach ( $dates as &$date ) {
// 			print "<p>1</p>";
			if (isset($date['datestamp'])) {
				$date['events'] = array();
				foreach ( $events as $event ) {
// 					print "<p>2</p>";
					if ( $event->getStartdate()->format('Y/m/d') == $date['datestamp'] ) {
						array_push($date['events'], $event);
					}
				}
			}
		}

		return $dates;
	}
}
