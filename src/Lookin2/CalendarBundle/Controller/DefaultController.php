<?php

// src/Lookin2/CalendarBundle/Controller/DefaultController.php

namespace Lookin2\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
	 * @Route("/month/{year}/{month}/{type}", name="month")
	 * @Route("/month/",                      name="month_no_param")
	 * 
	 * @Method("GET")
	 * 
	 * @Template("Lookin2CalendarBundle:Month:month.html.twig")
	 */
	public function monthAction($year = null, $month = null, $type = null)
	{
		// -- get a new calendar
		$calendar = $this->get('lookin2.calendar.month');

		// -- pass parameters
		$calendar->globalInit($year, $month);

		// -- get month dates -----------------------------------------------------
		$monthDates = $calendar->getMonthCalendarDates('month');

		// -- create parameters array 
		$params = array(
				'days'         => $monthDates,
				'PrevYearUrl'  => $calendar->getPrevYearUrl(),
				'PrevMonthUrl' => $calendar->getPrevMonthUrl(),
				'NextMonthUrl' => $calendar->getNextMonthUrl(),
				'NextYearUrl'  => $calendar->getNextYearUrl(),
				'CurrentMonth' => $calendar->getCurrentMonthStamp(),
		);


		// -- get the request for ajax detection
		$request = $this->container->get('request');

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
			else if ($type === 'content') {
				return $this->render(
						'Lookin2CalendarBundle:Month:container.html.twig',
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
		// -- get a new calendar
		$calendar = $this->get('lookin2.calendar.day');

		// -- pass parameters
		$calendar->globalInit($year, $month);

		// -- get month dates -----------------------------------------------------
		$monthDates = $calendar->getMonthCalendarDates('day');

		// -- get day times -------------------------------------------------------
		$dayTimes = $calendar->getDayTimes();

		// -- create parameters array
		$params = array(
				'days'         => $monthDates,
				'times'        => $dayTimes,
				'PrevYearUrl'  => $calendar->getPrevYearUrl(),
				'PrevMonthUrl' => $calendar->getPrevMonthUrl(),
				'NextMonthUrl' => $calendar->getNextMonthUrl(),
				'NextYearUrl'  => $calendar->getNextYearUrl(),
				'CurrentMonth' => $calendar->getCurrentMonthStamp(),
				'CurrentDay'   => $calendar->getCurrentMonthStamp(),
				'dayDate'      => $calendar->getYear().'/'.$calendar->getMonth().'/'.$day
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
