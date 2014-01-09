<?php

// src/Lookin2/CalendarBundle/Controller/DefaultController.php

namespace Lookin2\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Lookin2\CalendarBundle\Helpers\Calendar;

class DefaultController extends Controller
{	
	/**
	 * @Route("/")
	 * @Template()
	 */
	public function indexAction()
	{
		return array('msg' => 'app root');
	}

	/**
	 * @Route("/month/{year}/{month}/{type}", name="month")
	 * @Route("/month/")
	 * @Route("/month")
	 * @Template("Lookin2CalendarBundle:Month:month.html.twig")
	 */
	public function monthAction($year = null, $month = null, $type = null)
	{
		// -- get a new calendar
		$calendar = $this->get('lookin2.calendar');

		// -- pass parameters
		$calendar->setYear($year);
		$calendar->setMonth($month);

		// -- get month dates -----------------------------------------------------
		$monthDates = $calendar->getMonthCalendarDates();

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
			if ($type) {
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
	 * @Route("/day/")
	 * @Route("/day")
	 * @Template("Lookin2CalendarBundle:Day:day.html.twig")
	 */
	public function dayAction($year = null, $month = null, $day = null)
	{
		// -- get a new calendar
		$calendar = $this->get('lookin2.calendar');

		// -- pass parameters
		$calendar->setYear($year);
		$calendar->setMonth($month);

		// -- get month dates -----------------------------------------------------
		$monthDates = $calendar->getMonthCalendarDates();

		// -- create parameters array
		$params = array(
				'days'         => $monthDates,
				'PrevYearUrl'  => $calendar->getPrevYearUrl(),
				'PrevMonthUrl' => $calendar->getPrevMonthUrl(),
				'NextMonthUrl' => $calendar->getNextMonthUrl(),
				'NextYearUrl'  => $calendar->getNextYearUrl(),
				'CurrentMonth' => $calendar->getCurrentMonthStamp(),
				'dayDate'      => $year . '/' . $month . '/' . $day,
		);


		// -- get the request for ajax detection
		$request = $this->container->get('request');

		// -- ajax detection
		if($request->isXmlHttpRequest()) {
			return $this->render(
					'Lookin2CalendarBundle:Day:content.html.twig',
					$params
			);
		}

		// -- no ajax
		return $params;
	}
	
	
}
