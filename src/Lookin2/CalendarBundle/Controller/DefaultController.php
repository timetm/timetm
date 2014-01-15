<?php

// src/Lookin2/CalendarBundle/Controller/DefaultController.php

namespace Lookin2\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Session\Session;


use Lookin2\CalendarBundle\Helpers\Calendar;
use Lookin2\CalendarBundle\Helpers\CalendarDay;

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
	public function monthAction($year = null, $month = null, $day = null, $type = null)
	{

		// -- get the request
		$request = $this->container->get('request');

// 		if(!isset($_SESSION)) {
// 			// start session
// 			$session = new Session();
// 			$session->start();
// 		}
// 		else {
// 			$session = $request->getSession();
// 		}
		
// 		if (!$type) {
// 			// set current view
// 			$session->set('lookin2.current.view', 'month');
// 		}

		// -- get a new calendar
		$calendar = $this->get('lookin2.calendar.month');

		// -- pass common parameters
		$calendar->init($year, $month);

// 		if ( $session->has('lookin2.previous.month') and $session->get('lookin2.previous.month') == $month ) {
// 			$response = $this->forward('Lookin2CalendarBundle:Default:day', array(
// 					'month' => $calendar->getMonth(),
// 					'year'  => $calendar->getYear(),
// 			));

// 			return $response;
// 		}


// 		$session->set('lookin2.previous.month', $calendar->getMonth());
// 		$session->set('lookin2.previous.year',  $calendar->getYear());
		
		
		
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
				'CurrentMonthName'  => $calendar->getMonthName(),
				'CurrentYear'       => $calendar->getYear(),
		);

// 		echo $type;
		
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
			 * quick navigation
			* render main calendar
			*/
// 			else if ($type === 'content') {
// 				return $this->render(
// 						'Lookin2CalendarBundle:Month:content.html.twig',
// 						$params
// 				);
// 			}
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

		if ($type === 'content') {
			return $this->render(
					'Lookin2CalendarBundle:Month:content.html.twig',
					$params
			);
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
		
// 		if(!isset($_SESSION)) {
// 			// start session
// 			$session = new Session();
// 			$session->start();
// 		}
// 		else {
// 			$session = $request->getSession();
// 		}
		
		
		// set current view
// 		$session->set('lookin2.current.view', 'day');

		// -- get a new calendar
		$calendar = $this->get('lookin2.calendar.day');

		// -- initialize the calendar
		$calendar->init($year, $month, $day);

		// -- get month dates -----------------------------------------------------
		$monthDates = $calendar->getMonthCalendarDates('day');

		// -- get day times -------------------------------------------------------
		$dayTimes = $calendar->getDayTimes();

		// -- create parameters array
		$params = array(
				'days'              => $monthDates,
				'times'             => $dayTimes,
				// navigation
				'DayPrevYearUrl'    => $calendar->getPrevYearUrl('day'),
				'DayPrevMonthUrl'   => $calendar->getPrevMonthUrl('day'),
				
				'DayNextMonthUrl'   => $calendar->getNextMonthUrl('day'),
				'DayNextYearUrl'    => $calendar->getNextYearUrl('day'),
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
				'CurrentMonthName'  => $calendar->getMonthName(),
				'CurrentDay'        => $calendar->getCurrentDayStamp(),
		);


		// -- get the request for ajax detection
		$request = $this->container->get('request');

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

}
