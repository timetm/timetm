<?php 

/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\CoreBundle\Helper;

use TimeTM\CoreBundle\Entity\Event;

/**
 * Helper class for event
 *
 * @author André Friedli <a@frian.org>
 */
class EventHelper {

	/**
	 * Entity Manager
	 *
	 * @var EntityManager $em
	 */
	protected $em;
	
	/**
	 * Constructor
	 *
	 * @param EntityManager $em
	 */
	public function __construct(\Doctrine\ORM\EntityManager $em, $securityContext)
	{
		$this->em = $em;
		$this->context = $securityContext;
	}
	
	/**
	 * Fill event for form
	 * 
	 * @param      string    $year
	 * @param      string    $month
	 * @param      string    $day
	 * @param      string    $hour
	 * @param      string    $min
	 * 
	 * @return     \TimeTM\CoreBundle\Entity\Event
	 */
	public function fillNewEvent($year = null, $month = null, $day = null, $hour = null, $min = null) {

		$event = new Event();

		if ($year) {
			$startDate = date("Y-m-d H:i", mktime( date("H") + 1 , 0, 0, $month , $day , $year));
			$endDate = date("Y-m-d H:i", mktime( date("H") + 2 , 0, 0, $month , $day , $year));
		}
		else {
			$startDate = date("Y-m-d H:i", mktime( date("H") + 1 , 0, 0, date("n") , date("j") , date("Y")));
			$endDate = date("Y-m-d H:i", mktime( date("H") + 2 , 0, 0, date("n") , date("j") , date("Y")));
		}

		if ($hour) {
			$startDate = date("Y-m-d H:i", mktime( $hour , $min, 0, $month , $day , $year));
			$endDate = date("Y-m-d H:i", mktime( $hour + 1 , $min, 0, $month , $day , $year));
		}

		$event->setStartDate(new \DateTime($startDate));
// 		$event->setStartTime(new \DateTime($startTime));
		$event->setEndDate(new \DateTime($endDate));
// 		$event->setEndTime(new \DateTime($endTime));

		return $event;
	}


	public function getDashboardEvents() {

		// create array with tomorrow and after tomorrow
		$days = array();
		
		// get tomorrow's date
		$tomorrow = new \DateTime('tomorrow');

		\array_push($days, $tomorrow->format('Y-m-d'));
		\array_push($days, $tomorrow->modify('+1 day')->format('Y-m-d'));

		$events = array();

		foreach ( $days as $index=>$day ) {

			$qb = $this->em->createQueryBuilder();

			$results = $qb
			->select('e')
			->from('TimeTMCoreBundle:Event', 'e')
			->leftjoin('e.agenda', 'a')
			->leftjoin('a.user', 'u')
			->where('e.startdate = :day')
			->andWhere('a.user = :user')
			->setParameter('day', $day)
			->setParameter('user', $this->context->getToken()->getUser())
			->getQuery()
			->execute();

			if ($index == 0) {
				\array_push($events, $results);
			}
			else if ($index == 1) {
				\array_push($events, $results);
			}
		}
		
		return array($events, $days);
	}

}
