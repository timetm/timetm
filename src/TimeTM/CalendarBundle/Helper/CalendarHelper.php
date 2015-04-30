<?php
/**
 * This file is part of TimeTM
 *
 * @author André andre@at-info.ch
 */


namespace TimeTM\CalendarBundle\Helper;

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
	 * create the canonical user name
	 * 
	 * @param TimeTM\ContactBundle\Entity\Contact
	 * 
	 * @return     array     ($canonicalName, $msg)
	 */
	public function addEventsToCalendar(\TimeTM\CalendarBundle\Model\Calendar $calendar, array $monthDates) {

		// get date for first and last day of month
		$firstDayOfMonth = date( 'Y-m-d', mktime( 0, 0, 0, $calendar->getMonth(), 1, $calendar->getYear() ) );
		$lastDayOfMonth  = date( 'Y-m-d', mktime( 0, 0, 0, $calendar->getMonth(), date( 't', mktime( 0, 0, 0, $calendar->getMonth(), 1, $calendar->getYear() ) ), $calendar->getYear() ) );

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
		->from('TimeTMEventBundle:Event', 'e')
		->leftjoin('e.agenda', 'a')
		->leftjoin('a.user', 'u')
		->where('e.startdate BETWEEN :firstDay AND :lastDay')
		->setParameter('firstDay', $firstDayOfMonth)
		->setParameter('lastDay', $lastDayOfMonth)
		->getQuery()
		->execute();

		// add events to the monthDates array
		foreach ( $monthDates as &$date ) {
			if (isset($date['datestamp'])) {
				$date['events'] = array();
				foreach ( $events as $event ) {
					if ( $event->getStartdate()->format('Y-m-d') == $date['datestamp'] ) {
						array_push($date['events'], $event);
					}
				}
			}
		}

		return $monthDates;
	}
}
