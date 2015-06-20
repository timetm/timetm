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

/**
 * Helper class for calendar
 * 
 * @author André Friedli <a@frian.org>
 */
class CalendarHelper {

	/**
	 * Entity Manager
	 *
	 * @var EntityManager $em
	 */
	protected $em;

	/**
	 * Constructor
	 *
	 * @param EntityManager $em
	 */
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		$this->em = $em;
	}

	/**
	 * add events to an array of dates
	 * 
	 * @param      TimeTM\CoreBundle\Model\Calendar   $calendar
	 * @param      array                              $dates
	 * @param      string                             $type
	 * 
	 * @return     array                              $dates
	 */
	public function addEventsToCalendar(\TimeTM\CoreBundle\Model\Calendar $calendar, array $dates, $type = 'month') {

		if ($type == 'month') {
			// get date for first and last day of month
			$firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $calendar->getMonth(), 1, $calendar->getYear()));
			$lastDayOfMonth  = date('Y-m-d', mktime(0, 0, 0, $calendar->getMonth(), date('t', mktime(0, 0, 0, $calendar->getMonth(), 1, $calendar->getYear())), $calendar->getYear()));
		}
		elseif ($type == 'week') {
			$firstDayOfMonth = $calendar->getFirstDateOfWeek('Y-m-d');
			$lastDayOfMonth  = $calendar->getLastDateOfWeek('Y-m-d');
		}
		elseif ($type == 'day') {
			$firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $calendar->getMonth(), $calendar->getDay(), $calendar->getYear()));
			$lastDayOfMonth  = date('Y-m-d', mktime(0, 0, 0, $calendar->getMonth(), $calendar->getDay(), $calendar->getYear()));
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

		// add events to the dates array
		foreach ( $dates as &$date ) {
			if (isset($date['datestamp'])) {
				$date['events'] = array();
				foreach ( $events as $event ) {
					if ( $event->getStartdate()->format('Y/m/d') == $date['datestamp'] ) {
						array_push($date['events'], $event);
					}
				}
			}
		}

		return $dates;
	}

	/**
	 * get common template parameters
	 *
	 * @param      TimeTM\CoreBundle\Model\Calendar   $calendar
	 * @param      string                             $type
	 *
	 * @return     array                              $params
	 */
	public function getBaseTemplateParams(\TimeTM\CoreBundle\Model\Calendar $calendar, $type = 'month') {

		$params = array(
			// panel quick navigation
			'MonthPrevYearUrl'  => $calendar->getYearUrl('month', 'prev'),
			'MonthPrevMonthUrl' => $calendar->getPrevMonthUrl('month'),
			'MonthNextMonthUrl' => $calendar->getNextMonthUrl('month'),
			'MonthNextYearUrl'  => $calendar->getYearUrl('month', 'next'),
			'MonthName'         => $calendar->getMonthName(),
			'Year'              => $calendar->getYear()
		);

		if ($type == 'month') {
			$params['ModeDayUrl']  = $calendar->getDayUrl();
			$params['ModeWeekUrl'] = $calendar->getModeChangeUrl('week');
		}
		elseif ($type == 'day') {
			$params['days'] = $calendar->getMonthCalendarDates();
			// panel navigation
			$params['DayPrevYearUrl']  = $calendar->getYearUrl('day', 'prev');
			$params['DayPrevMonthUrl'] = $calendar->getPrevMonthUrl('day');
			$params['DayNextMonthUrl'] = $calendar->getNextMonthUrl('day');
			$params['DayNextYearUrl']  = $calendar->getYearUrl('day', 'next');
			$params['YesterdayUrl']    = $calendar->getYesterdayUrl();
			$params['TomorrowUrl']     = $calendar->getTomorrowUrl();
			$params['ModeMonthUrl']    = $calendar->getModeChangeUrl('month');
			$params['ModeWeekUrl']     = $calendar->getModeChangeUrl('week');
			$params['CurrentDay']      = $calendar->getCurrentDayStamp();
		}
		elseif ($type == 'week') {
			$params['days'] = $calendar->getMonthCalendarDates();
			// panel navigation
			$params['WeekPrevYearUrl'] = $calendar->getYearUrl('week', 'prev');
			$params['WeekNextYearUrl'] = $calendar->getYearUrl('week', 'next');
			$params['WeekPrevWeekUrl'] = $calendar->getPrevWeekUrl();
			$params['WeekNextWeekUrl'] = $calendar->getNextWeekUrl();
			$params['ModeMonthUrl']    = $calendar->getModeChangeUrl('month');
			$params['ModeDayUrl']      = $calendar->getDayUrl();
			$params['WeekStamp']       = $calendar->getWeekStamp();
		}

		return $params;
	}
}
