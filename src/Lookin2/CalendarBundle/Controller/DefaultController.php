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
	 * @Route("/month")
	 * @Route("/month/")
	 * @Template()
	 */
	public function monthAction($year = null, $month = null, $type = null)
	{
		$request = $this->container->get('request');
		
		$calendar = $this->get('lookin2.calendar');
		$calendar->setYear($year);
		$calendar->setMonth($month);

		// -- build monthly navigation links --------------------------------------
		$PrevYearUrl  = $calendar->getPrevYearUrl();
		$PrevMonthUrl = $calendar->getPrevMonthUrl();
		$NextYearUrl  = $calendar->getNextYearUrl();
		$NextMonthUrl = $calendar->getNextMonthUrl();
		
		// -- get month dates -----------------------------------------------------
		$monthDates = $calendar->getMonthCalendarDates();
		
		if($request->isXmlHttpRequest())
		{
			return $this->render(
					'Lookin2CalendarBundle:Default:panelCalendar.html.twig',
					array(
						'days' => $monthDates,
							'PrevMonthUrl' => $PrevMonthUrl,
							'NextMonthUrl' => $NextMonthUrl,
							'PrevYearUrl'  => $PrevYearUrl,
							'NextYearUrl'  => $NextYearUrl,
							'CurrentMonth' => $calendar->getCurrentMonthStamp(),
					)
			);
		}
		
		// -- fill template -------------------------------------------------------
		return array(
			'days'         => $monthDates, 
			'PrevMonthUrl' => $PrevMonthUrl,
			'NextMonthUrl' => $NextMonthUrl,
			'PrevYearUrl'  => $PrevYearUrl,
			'NextYearUrl'  => $NextYearUrl,
			'CurrentMonth' => $calendar->getCurrentMonthStamp(),
		);
	}

	
	/**
	 * @Route("/day/{year}/{month}/{day}", name="day")
	 * @Route("/day")
	 * @Route("/day/")
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
