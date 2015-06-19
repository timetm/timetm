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

namespace TimeTM\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
 * Calendar controller.
 */
class CalendarController extends Controller {

	/**
	 * Create a calendar month
	 *
	 * @param      integer   $year        	
	 * @param      integer   $month        	
	 * @param      string    $type        	
	 * 
	 * @Route("/month/{year}/{month}/{type}", name="month")
	 * @Route("/month/",                      name="month_no_param")
	 * 
	 * @Method("GET")
	 */
	public function monthAction($year = null, $month = null, $type = null) {

		// get a new calendar
		$calendar = $this->get('timetm.calendar.month');

		// initialize the calendar
		$calendar->init(array(
			'year' => $year,
			'month' => $month,
			'type' => $type 
		));

		// get a helper
		$helper = $this->get('timetm.calendar.helper');

		// get month dates
		$monthDates = $calendar->getMonthCalendarDates();

		// add events
		$monthDates = $helper->addEventsToCalendar($calendar, $monthDates);

		// get common template params
		$params = $helper->getBaseTemplateParams($calendar);

		// add template params
		$params['days'] = $monthDates;

		// get the request
		$request = $this->container->get('request');

		// ajax detection
		if ($request->isXmlHttpRequest()) {
			/*
			 * quick navigation
			 * render panel calendar
			 */
			if ($type === 'panel') {
				return $this->render ('TimeTMCoreBundle:Calendar:Default/panelCalendar.html.twig', $params);
			}
			/*
			 * normal navigation
			 * render panel and main calendar
			 */
			else {
				return $this->render('TimeTMCoreBundle:Calendar:Month/container.html.twig', $params);
			}
		}
		
		// no ajax
		return $this->render('TimeTMCoreBundle:Calendar:Month/month.html.twig', $params);
	}
	
	/**
	 * Create a calendar day
	 *
	 * @param      integer   $year        	
	 * @param      integer   $month        	
	 * @param      integer   $day
	 * 
	 * @Route("/day/{year}/{month}/{day}", name="day")
	 * @Route("/day/",                     name="day_no_param")
	 *        	
	 * @Method("GET")
	 */
	public function dayAction($year = null, $month = null, $day = null) {

		// get a new calendar
		$calendar = $this->get('timetm.calendar.day');

		// initialize the calendar
		$calendar->init (array(
			'year' => $year,
			'month' => $month,
			'day' => $day 
		) );

		// get a helper
		$helper = $this->get('timetm.calendar.helper');

		// get times
		$times = $this->get('timetm.calendar.times');

		// get an array with daystamp
		$dayStamp = $calendar->getYear() . '/'. $calendar->getMonth() . '/'.  $calendar->getDay();
		$dayDate = array();
		array_push($dayDate, array('datestamp' => $dayStamp));

		// add events
		$dayDate = $helper->addEventsToCalendar($calendar, $dayDate, 'day');

		// get common template params
		$params = $helper->getBaseTemplateParams($calendar, 'day');

		// -- add template params
		$params['times'] = $times->getDayTimes();
		$params['day'] = $dayDate;
		$params['dayStamp'] = $dayStamp;
		
		// get the request for ajax detection
		$request = $this->container->get ('request');

		// ajax detection
		if ($request->isXmlHttpRequest ()) {
			return $this->render ('TimeTMCoreBundle:Calendar:Day/container.html.twig', $params);
		}
		
		// no ajax
		return $this->render('TimeTMCoreBundle:Calendar:Day/day.html.twig', $params);
	}
	
	/**
	 * Create a calendar week
	 *
	 * @param      integer   $year        	
	 * @param      integer   $weekno
	 * 
	 * @Route("/week/{year}/{weekno}", name="week")
	 * @Route("/week/",                name="week_no_param")
	 * 
	 * @Method("GET")
	 */
	public function weekAction($year = null, $weekno = null) {

		// get a new calendar
		$calendar = $this->get('timetm.calendar.week');

		// initialize the calendar
		$calendar->init(array(
			'year' => $year,
			'weekno' => $weekno 
		));

		// get a helper
		$helper = $this->get('timetm.calendar.helper');

		// get times
		$times = $this->get('timetm.calendar.times');

		// get week dates
		$weekDates = $calendar->getWeekCalendarDates();

		// add events
		$weekDates = $helper->addEventsToCalendar($calendar, $weekDates, 'week');

		// get common template params
		$params = $helper->getBaseTemplateParams($calendar, 'week');

		// -- add template params
		$params['times'] = $times->getDayTimes();
		$params['weekDates'] = $weekDates;

		// get the request for ajax detection
		$request = $this->container->get('request');

		// ajax detection
		if ($request->isXmlHttpRequest()) {
			return $this->render( 'TimeTMCoreBundle:Calendar:Week/container.html.twig', $params );
		}

		// no ajax
		return $this->render( 'TimeTMCoreBundle:Calendar:Week/week.html.twig', $params );
	}
}
