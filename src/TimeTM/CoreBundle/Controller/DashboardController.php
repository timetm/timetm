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


		// get events
		list($events, $days) = $this->get('timetm.event.helper')->getDashboardEvents();

		// set params
        $params = array(
            'events'    => $events,
            'eventdays' => $days,
            'template'  => 'index'
        );

        // ajax detection
        if ($request->isXmlHttpRequest()) {
        	$params['buttonText'] = 'action.close';
        	return $this->render( 'TimeTMCoreBundle:Dashboard:index.html.twig', $params );
        }

		// get a new calendar
		$calendar = $this->get('timetm.calendar.month');

		// initialize the calendar
		$calendar->init( array(
			'year' => date('Y'),
			'month' => date('m')
		));

		// get common template params
		$params = \array_merge($params,$this->get('timetm.calendar.helper')->getBaseTemplateParams($calendar));

		return $this->render ( 'TimeTMCoreBundle:Dashboard:dashboard.html.twig', $params );
	}
}
