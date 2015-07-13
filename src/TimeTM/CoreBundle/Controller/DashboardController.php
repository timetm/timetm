<?php
/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Dashboard controller.
 * 
 * @author André Friedli <a@frian.org>
 */
class DashboardController extends Controller
{
	/**
	 * Empty home page
	 *
	 * @Route("/", name="dashboard")
	 * @Method("GET")
	 */
	public function indexAction(Request $request) {

		// get a new calendar
		$calendar = $this->get('timetm.calendar.month');

		// initialize the calendar
		$calendar->init( array (
			'year' => date('Y'),
			'month' => date('m'),
		));

		// get a calendar helper
		$calHelper = $this->get('timetm.calendar.helper');

		// get a event helper
		$eventHelper = $this->get('timetm.event.helper');

		// get common template params
		$params = $calHelper->getBaseTemplateParams($calendar);

		// get events
		list($events, $days) = $eventHelper->getDashboardEvents();

		// set params
		$params['events'] = $events;
		$params['eventdays'] = $days;

		return $this->render ( 'TimeTMCoreBundle:Dashboard:index.html.twig', $params );
	}
}
