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

		// get common template params
		$params = $calHelper->getBaseTemplateParams($calendar);



		// create array with tomorrow and after tomorrow
		$days = array();

		// get tomorrow's date
		$tomorrow = new \DateTime('tomorrow');

		\array_push($days, $tomorrow->format('Y-m-d'));
		\array_push($days, $tomorrow->modify('+1 day')->format('Y-m-d'));

		$em = $this->getDoctrine()->getManager();

		$events = array();

		foreach ( $days as $index=>$day ) {
		
			$qb = $em->createQueryBuilder();
		
			$results = $qb
			->select('e')
			->from('TimeTMCoreBundle:Event', 'e')
			->leftjoin('e.agenda', 'a')
			->leftjoin('a.user', 'u')
			->where('e.startdate = :day')
			->andWhere('a.user = :user')
			->setParameter('day', $day)
			->setParameter('user', $this->getUser())
			->getQuery()
			->execute();

			if ($index == 0) {
				\array_push($events, $results);
			}
			else if ($index == 1) {
				\array_push($events, $results);
			}

		}



		$params['events'] = $events;
		$params['eventdays'] = $days;
		
		return $this->render ( 'TimeTMCoreBundle:Dashboard:index.html.twig', $params );
	}
}
