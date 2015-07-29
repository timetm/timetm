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


	/**
	 * Get events for a user's dashboard (today and tomorrow)
	 *
	 * @return     array of \TimeTM\CoreBundle\Entity\Event
	 */
	public function getDashboardEvents() {

		/*
		 * create array with today and tomorrow
		 */
		$days = array();
		$today = new \DateTime();
		\array_push($days, $today->format('Y-m-d'));
		\array_push($days, $today->modify('+1 day')->format('Y-m-d'));

		$events = array();

		foreach ( $days as $index=>$day ) {

			// create a local DateTime object
			$localDay = new \DateTime($day);

			// get events
			$results = $this->getUserEvents($this->context->getToken()->getUser(),
				$localDay->format('Y-m-d'),
				$localDay->modify('+1 day')->format('Y-m-d'));

			if ($index == 0) {
				\array_push($events, $results);
			}
			else if ($index == 1) {
				\array_push($events, $results);
			}
		}

		return array($events, $days);
	}


	/**
	 * Get events for a user between two dates
	 *
	 * @param      user      $user
	 * @param      string    $startDate
	 * @param      string    $endDate
	 *
	 * @return     array of \TimeTM\CoreBundle\Entity\Event
	 */
	public function getUserEvents($user , $startDate, $endDate) {

		$qb = $this->em->createQueryBuilder();

		return $qb
			->select('e')
			->from('TimeTMCoreBundle:Event', 'e')
			->leftjoin('e.agenda', 'a')
			->leftjoin('a.user', 'u')
			->where('e.startdate BETWEEN :startDate AND :endDate')
			->andWhere('a.user = :user')
			->setParameter('startDate', $startDate)
			->setParameter('endDate', $endDate)
			->setParameter('user', $user)
			->addOrderBy('e.startdate', 'ASC')
			->getQuery()
			->execute();
	}

}

















