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
	 * @Template()
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
						'Lookin2CalendarBundle:Default:calendarContainer.html.twig',
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
	 * @Template("Lookin2CalendarBundle:Default:index.html.twig")
	 */
	public function dayAction($year = null, $month = null, $day = null)
	{
		return array('msg' => 'app root');
// 		$calendar = $this->get('lookin2.calendar');
// 		$calendar->setYear($year);
// 		$calendar->setMonth($month);
	
// 		// -- build monthly navigation links --------------------------------------
// 		$PrevYearUrl  = $calendar->getPrevYearUrl();
// 		$PrevMonthUrl = $calendar->getPrevMonthUrl();
// 		$NextYearUrl  = $calendar->getNextYearUrl();
// 		$NextMonthUrl = $calendar->getNextMonthUrl();
	
// 		// -- get month dates -----------------------------------------------------
// 		$monthDates = $calendar->getMonthCalendarDates();
	
// 		// -- fill template -------------------------------------------------------
// 		return array(
// 				'days'         => $monthDates,
// 				'PrevMonthUrl' => $PrevMonthUrl,
// 				'NextMonthUrl' => $NextMonthUrl,
// 				'PrevYearUrl'  => $PrevYearUrl,
// 				'NextYearUrl'  => $NextYearUrl,
// 				'CurrentMonth' => $calendar->getCurrentMonth(),
// 		);
	}
	
	
}
