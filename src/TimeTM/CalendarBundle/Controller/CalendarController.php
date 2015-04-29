<?php
/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace TimeTM\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use TimeTM\CalendarBundle\Model\CalendarMonth;

/**
 * Calendar controller.
 */
class CalendarController extends Controller {

	/**
	 * Empty home page
	 *
	 * @Route("/", name="app_home")
	 * @Method("GET")
	 */
	public function indexAction() {
		return $this->render ( 'TimeTMCalendarBundle:Default:index.html.twig', array(
			'msg' => 'index' 
		));
	}

	/**
	 * Create a calendar month
	 *
	 * @param integer $year        	
	 * @param integer $month        	
	 * @param string $type        	
	 *
	 * @return CalendarMonth 
	 * 
	 * @Route("/month/{year}/{month}/{type}", name="month")
	 * @Route("/month/", name="month_no_param")
	 * 
	 * @Method("GET")
	 */
	public function monthAction($year = null, $month = null, $type = null) {

		// get a new calendar
		$calendar = $this->get('timetm.calendar.month');

		// initialize the calendar
		$calendar->init( array (
			'year' => $year,
			'month' => $month,
			'type' => $type 
		));

		// get date for first and last day of month
		$firstDayOfMonth = date( 'Y-m-d', mktime( 0, 0, 0, $calendar->getMonth(), 1, $calendar->getYear() ) );
		$lastDayOfMonth  = date( 'Y-m-d', mktime( 0, 0, 0, $calendar->getMonth(), date( 't', mktime( 0, 0, 0, $calendar->getMonth(), 1, $calendar->getYear() ) ), $calendar->getYear() ) );

		// get query builder			
		$queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();

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
			->select('partial e.{id, title, place, startdate }')
			->from('TimeTMEventBundle:Event', 'e')
			->leftjoin('e.agenda', 'a')
			->leftjoin('a.user', 'u')
	    	->where('e.startdate BETWEEN :firstDay AND :lastDay')
		    ->setParameter('firstDay', $firstDayOfMonth)
		    ->setParameter('lastDay', $lastDayOfMonth)
		    ->getQuery()
		    ->execute();

		// Possible futur contextual navigation
		//
		// if ( $session->has('timetm.previous.month') and $session->get('timetm.previous.month') == $month ) {
		// $response = $this->forward('TimeTMCalendarBundle:Default:day', array(
		// 'month' => $calendar->getMonth(),
		// 'year' => $calendar->getYear(),
		// ));
		// return $response;
		// }
		
		// -- get month dates -------------------------------------------------
		$monthDates = $calendar->getMonthCalendarDates();

		// add events to the monthDates array
		foreach ( $monthDates as &$date ) {
			if (isset($date['datestamp'])) {
				$date['events'] = array();
				foreach ( $events as $event ) {
					if ( $event->getStartDate()->format('Y-m-d') == $date['datestamp'] ) {
						array_push($date['events'], $event);
					}
				}
			}
		}

		// -- create parameters array
		$params = array (
			// content
			'days' => $monthDates,
			// panel navigation
			'MonthPrevYearUrl' => $calendar->getPrevYearUrl('month'),
			'MonthPrevMonthUrl' => $calendar->getPrevMonthUrl('month'),
			'MonthNextMonthUrl' => $calendar->getNextMonthUrl('month'),
			'MonthNextYearUrl' => $calendar->getNextYearUrl('month'),
			// mode navigation
			'ModeDayUrl' => $calendar->getDayUrl(),
			'ModeWeekUrl' => $calendar->getModeChangeUrl('week'),
			//
			'MonthName' => $calendar->getMonthName(),
			'CurrentYear' => $calendar->getYear() 
		);

		// get the request
		$request = $this->container->get('request');

		// -- ajax detection
		if ($request->isXmlHttpRequest ()) {
			/*
			 * quick navigation
			 * render panel calendar
			 */
			if ($type === 'panel') {
				return $this->render ( 'TimeTMCalendarBundle:Default:panelCalendar.html.twig', $params );
			}
			/*
			 * normal navigation
			 * render panel and main calendar
			 */
			else {
				return $this->render( 'TimeTMCalendarBundle:Month:container.html.twig', $params );
			}
		}
		
		// -- no ajax
		return $this->render( 'TimeTMCalendarBundle:Month:month.html.twig', $params );
	}
	
	/**
	 * Create a calendar day
	 *
	 * @param integer $year        	
	 * @param integer $month        	
	 * @param integer $day
	 * 
	 * @Route("/day/{year}/{month}/{day}", name="day")
	 * @Route("/day/", name="day_no_param")
	 *        	
	 * @Method("GET")
	 */
	public function dayAction($year = null, $month = null, $day = null) {
		
		// -- get the request for ajax detection
		$request = $this->container->get ( 'request' );
		
		// -- get a new calendar
		$calendar = $this->get ( 'timetm.calendar.day' );
		
		// -- initialize the calendar
		$calendar->init ( array (
			'year' => $year,
			'month' => $month,
			'day' => $day 
		) );
		
		// -- get month dates -----------------------------------------------------
		$monthDates = $calendar->getMonthCalendarDates();
		
		// -- get times
		$times = $this->get ( 'timetm.calendar.times' );
		
		// -- create parameters array
		$params = array (
				
			// content
			'days' => $monthDates,
			'times' => $times->getDayTimes (),
			// navigation
			'DayPrevYearUrl' => $calendar->getPrevYearUrl ( 'day' ),
			'DayPrevMonthUrl' => $calendar->getPrevMonthUrl ( 'day' ),
			'DayNextMonthUrl' => $calendar->getNextMonthUrl ( 'day' ),
			'DayNextYearUrl' => $calendar->getNextYearUrl ( 'day' ),
			// panel
			
			// panel navigation
			'MonthPrevYearUrl' => $calendar->getPrevYearUrl ( 'month' ),
			'MonthPrevMonthUrl' => $calendar->getPrevMonthUrl ( 'month' ),
			'YesterdayUrl' => $calendar->getYesterdayUrl (),
			'TomorrowUrl' => $calendar->getTomorrowUrl (),
			'MonthNextMonthUrl' => $calendar->getNextMonthUrl ( 'month' ),
			'MonthNextYearUrl' => $calendar->getNextYearUrl ( 'month' ),
			// mode navigation
			'ModeMonthUrl' => $calendar->getModeChangeUrl ( 'month' ),
			'ModeWeekUrl' => $calendar->getModeChangeUrl ( 'week' ),
			//
			'DayName' => $calendar->getDayName (),
			'MonthName' => $calendar->getMonthName (),
			'CurrentDay' => $calendar->getCurrentDayStamp () 
		);
		
		// -- ajax detection
		if ($request->isXmlHttpRequest ()) {
			return $this->render ( 'TimeTMCalendarBundle:Day:container.html.twig', $params );
		}
		
		// -- no ajax
		return $this->render( 'TimeTMCalendarBundle:Day:day.html.twig', $params );
	}
	
	/**
	 * Create a calendar week
	 *
	 * @param integer $year        	
	 * @param integer $weekno
	 * 
	 * @Route("/week/{year}/{weekno}", name="week")
	 * @Route("/week/", name="week_no_param")
	 * 
	 * @Method("GET")
	 */
	public function weekAction($year = null, $weekno = null) {

		// -- get the request for ajax detection
		$request = $this->container->get( 'request' );

		// -- get a new calendar
		$calendar = $this->get( 'timetm.calendar.week' );

		// -- initialize the calendar
		$calendar->init( array (
			'year' => $year,
			'weekno' => $weekno 
		) );

		// -- get times
		$times = $this->get( 'timetm.calendar.times' );

		// -- get week dates ------------------------------------------------------
		$weekDates = $calendar->getWeekCalendarDates();

		$calendar->getNextWeekUrl();

		// -- create parameters array
		$params = array (

			// content
			'days' => $calendar->getMonthCalendarDates(),
			'times' => $times->getDayTimes(),
			'weekDates' => $weekDates,
			// navigation
			'WeekPrevYearUrl' => $calendar->getPrevYearUrl( 'week' ),
			'WeekNextYearUrl' => $calendar->getNextYearUrl( 'week' ),
			'WeekPrevWeekUrl' => $calendar->getPrevWeekUrl(),
			'WeekNextWeekUrl' => $calendar->getNextWeekUrl(),
			// panel
			'WeekStamp' => $calendar->getWeekStamp(),
			// panel navigation
			'MonthName' => $calendar->getMonthName(),
			'MonthPrevYearUrl' => $calendar->getPrevYearUrl( 'month' ),
			'MonthPrevMonthUrl' => $calendar->getPrevMonthUrl( 'month' ),
			'MonthNextMonthUrl' => $calendar->getNextMonthUrl( 'month' ),
			'MonthNextYearUrl' => $calendar->getNextYearUrl( 'month' ),
			// mode navigation
			'ModeMonthUrl' => $calendar->getModeChangeUrl( 'month' ),
			'ModeDayUrl' => $calendar->getDayUrl() 
		);

		// -- ajax detection
		if ($request->isXmlHttpRequest()) {
			return $this->render( 'TimeTMCalendarBundle:Week:container.html.twig', $params );
		}

		// -- no ajax
		return $this->render( 'TimeTMCalendarBundle:Week:week.html.twig', $params );
	}
}
