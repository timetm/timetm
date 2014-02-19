<?php

// src/Lookin2/CalendarBundle/Controller/DefaultController.php

namespace Lookin2\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Session\Session;


use Lookin2\CalendarBundle\Model\Calendar;
use Lookin2\CalendarBundle\Model\CalendarDay;

class DefaultController extends Controller
{	
	/**
	 * @Route("/", name="app_home")
	 * 
	 * @Method("GET")
	 * 
	 * @Template()
	 */
	public function indexAction()
	{
		return array('msg' => $this->get('request')->getLocale());
	}

	/**
	 * @Route("/month/{year}/{month}/{type}",       name="month")
	 * @Route("/month/",                            name="month_no_param")
	 * 
	 * @Method("GET")
	 * 
	 * @Template("Lookin2CalendarBundle:Month:month.html.twig")
	 */
	public function monthAction( $year = null, $month = null, $type = null)
	{

		// -- get the request
		$request = $this->container->get('request');

		// -- get a new calendar
		$calendar = $this->get('lookin2.calendar.month');

		// -- initialize the calendar
		$calendar->init(array(
			'year'  => $year,
			'month' => $month,
			'type'  => $type,
		)) ;

// Possible futur contextual navigation
//
// 		if ( $session->has('lookin2.previous.month') and $session->get('lookin2.previous.month') == $month ) {
// 			$response = $this->forward('Lookin2CalendarBundle:Default:day', array(
// 					'month' => $calendar->getMonth(),
// 					'year'  => $calendar->getYear(),
// 			));
// 			return $response;
// 		}


		// -- get month dates -----------------------------------------------------
		$monthDates = $calendar->getMonthCalendarDates('day');

		// -- create parameters array 
		$params = array(
				'days'              => $monthDates,
				// panel navigation
				'MonthPrevYearUrl'  => $calendar->getPrevYearUrl('month'),
				'MonthPrevMonthUrl' => $calendar->getPrevMonthUrl('month'),
				'MonthNextMonthUrl' => $calendar->getNextMonthUrl('month'),
				'MonthNextYearUrl'  => $calendar->getNextYearUrl('month'),
				// mode navigation
				'ModeDayUrl'        => $calendar->getDayUrl('day', '01'),
				// 
				'MonthName'         => $calendar->getMonthName(),
				'CurrentYear'       => $calendar->getYear(),
		);

		// -- ajax detection
		if($request->isXmlHttpRequest()) {
			/*
			 * quick navigation
			 * render panel calendar
			 */
			if ($type === 'panel') {
				return $this->render(
					'Lookin2CalendarBundle:Default:panelCalendar.html.twig',
					$params
				);
			}
			/*
			 * normal navigation
			 * render panel and main calendar
			 */
			else {
				return $this->render(
						'Lookin2CalendarBundle:Month:container.html.twig',
						$params
				);
			}
		}

		// -- no ajax
		return $params;
	}


	/**
	 * @Route("/day/{year}/{month}/{day}", name="day")
	 * @Route("/day/",                     name="day_no_param")
	 * 
	 * @Method("GET")
	 * 
	 * @Template("Lookin2CalendarBundle:Day:day.html.twig")
	 */
	public function dayAction($year = null, $month = null, $day = null)
	{
		// -- get the request for ajax detection
		$request = $this->container->get('request');

		// -- get a new calendar
		$calendar = $this->get('lookin2.calendar.day');

		// -- initialize the calendar
		$calendar->init(array(
			'year'  => $year,
			'month' => $month,
			'day'   => $day,
		)) ;

		// -- get month dates -----------------------------------------------------
		$monthDates = $calendar->getMonthCalendarDates('day');

		// -- get day times -------------------------------------------------------
		$dayTimes = $calendar->getDayTimes();

		// -- create parameters array
		$params = array(
				// content
				'days'              => $monthDates,
				'times'             => $dayTimes,
				// navigation
				'DayPrevYearUrl'    => $calendar->getPrevYearUrl('day'),
				'DayPrevMonthUrl'   => $calendar->getPrevMonthUrl('day'),
				
				'DayNextMonthUrl'   => $calendar->getNextMonthUrl('day'),
				'DayNextYearUrl'    => $calendar->getNextYearUrl('day'),
				// panel
		
				// panel navigation
				'MonthPrevYearUrl'  => $calendar->getPrevYearUrl('month'),
				'MonthPrevMonthUrl' => $calendar->getPrevMonthUrl('month'),
				'YesterdayUrl'      => $calendar->getYesterdayUrl(),
				'TomorrowUrl'       => $calendar->getTomorrowUrl(),
				'MonthNextMonthUrl' => $calendar->getNextMonthUrl('month'),
				'MonthNextYearUrl'  => $calendar->getNextYearUrl('month'),
				// mode navigation
				'ModeMonthUrl'      => $calendar->getDayUrl('month'),
				// 
				'DayName'           => $calendar->getDayName(),
				'MonthName'         => $calendar->getMonthName(),
				'CurrentDay'        => $calendar->getCurrentDayStamp(),
		);


		// -- ajax detection
		if($request->isXmlHttpRequest()) {
			return $this->render(
					'Lookin2CalendarBundle:Day:container.html.twig',
					$params
			);
		}

		// -- no ajax
		return $params;
	}


	/**
	 * @Route("/week/{year}/{weekno}", name="week")
	 * @Route("/week/",                name="week_no_param")
	 *
	 * @Method("GET")
	 *
	 * @Template("Lookin2CalendarBundle:Week:week.html.twig")
	 */
	public function weekAction($year = null, $weekno = null)
	{
		// -- get the request for ajax detection
		$request = $this->container->get('request');
		
		// -- get a new calendar
		$calendar = $this->get('lookin2.calendar.week');
		
		// -- initialize the calendar
		$calendar->init(array(
				'year'   => $year,
				'weekno' => $weekno,
		)) ;


		// -- get day times 
		$times = $this->get('lookin2.calendar.times');
		

		// -- get week dates ------------------------------------------------------
		$weekDates = $calendar->getWeekCalendarDates();

		// -- create parameters array
		$params = array(
				// content
				'days'              => $calendar->getMonthCalendarDates('day'),
				'times'             => $times->getDayTimes(),
				'weekDates'         => $weekDates,
				// navigation

				// panel
				'WeekStamp'         => $calendar->getWeekStamp(),
				// panel navigation
				'MonthName'  => $calendar->getMonthName(),
				'MonthPrevYearUrl'  => $calendar->getPrevYearUrl('month'),
				'MonthPrevMonthUrl' => $calendar->getPrevMonthUrl('month'),
				'MonthNextMonthUrl' => $calendar->getNextMonthUrl('month'),
				'MonthNextYearUrl'  => $calendar->getNextYearUrl('month'),
				// mode navigation
				'ModeMonthUrl'      => $calendar->getDayUrl('month'),
		);
		
		// -- no ajax
		return $params;
	}

}
