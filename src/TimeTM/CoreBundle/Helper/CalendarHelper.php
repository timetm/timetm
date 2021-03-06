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

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Helper class for calendar
 *
 * @author André Friedli <a@frian.org>
 */
class CalendarHelper {

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
	public function __construct(\Doctrine\ORM\EntityManager $em, $securityContext, $eventHelper, $services) {
		$this->em = $em;
		$this->context = $securityContext;
		$this->eventHelper = $eventHelper;
        $this->services = $services;
	}

	/**
	 * add events to an array of dates
	 *
	 * @param      TimeTM\CoreBundle\Model\Calendar   $calendar
	 * @param      array                              $dates
	 * @param      string                             $type
	 *
	 * @return     array                              $dates
	 */
	public function addEventsToCalendar(\TimeTM\CoreBundle\Model\Calendar $calendar, array $dates, $type = 'month') {

		// get user
		$user = $this->context->getToken()->getUser();

		/**
		 * get start and end date
		 */
		if ($type == 'month') {
			// get date for first and last day of month
			$startDate = date('Y-m-d', mktime(0, 0, 0, $calendar->getMonth(), 1, $calendar->getYear()));
			$endDate  = date('Y-m-d H:i:s', mktime(23, 59, 59, $calendar->getMonth(), date('t', mktime(0, 0, 0, $calendar->getMonth(), 1, $calendar->getYear())), $calendar->getYear()));
		}
		elseif ($type == 'week') {
			$startDate = $calendar->getFirstDateOfWeek('Y-m-d');
			$endDate  = $calendar->getLastDateOfWeek('Y-m-d');
		}
		elseif ($type == 'day') {
			$startDate = date('Y-m-d', mktime(0, 0, 0, $calendar->getMonth(), $calendar->getDay(), $calendar->getYear()));
			$endDate = date('Y-m-d', mktime(0, 0, 0, $calendar->getMonth(), $calendar->getDay() + 1, $calendar->getYear()));
		}

		// get events
		$events = $this->eventHelper->getUserEvents($user , $startDate, $endDate, true);

		// add events to the dates array
		foreach ( $dates as &$date ) {
			if (isset($date['datestamp'])) {
				$date['events'] = array();
				foreach ( $events as $event ) {
					if ( $event->getStartdate()->format('Y/m/d') == $date['datestamp'] ) {
						array_push($date['events'], $event);
					}
				}
			}
		}

		return $dates;
	}

	/**
	 * get common template parameters
	 *
	 * @param      TimeTM\CoreBundle\Model\Calendar   $calendar
	 * @param      string                             $type
	 *
	 * @return     array                              $params
	 */
	public function getBaseTemplateParams(\TimeTM\CoreBundle\Model\Calendar $calendar, $type = 'month') {

		$params = array(
			// panel quick navigation
			'MonthPrevYearUrl'  => $calendar->getYearUrl('month', 'prev'),
			'MonthPrevMonthUrl' => $calendar->getPrevMonthUrl('month'),
			'MonthNextMonthUrl' => $calendar->getNextMonthUrl('month'),
			'MonthNextYearUrl'  => $calendar->getYearUrl('month', 'next'),
			'MonthName'         => $calendar->getMonthName(),
			'Year'              => $calendar->getYear(),
			'days'              => $calendar->getMonthCalendarDates()
		);

		if ($type == 'month') {
			$params['ModeDayUrl']  = $calendar->getDayUrl();
			$params['ModeWeekUrl'] = $calendar->getModeChangeUrl('week');
		}
		elseif ($type == 'day') {
			// panel navigation
			$params['DayPrevYearUrl']  = $calendar->getYearUrl('day', 'prev');
			$params['DayPrevMonthUrl'] = $calendar->getPrevMonthUrl('day');
			$params['DayNextMonthUrl'] = $calendar->getNextMonthUrl('day');
			$params['DayNextYearUrl']  = $calendar->getYearUrl('day', 'next');
			$params['YesterdayUrl']    = $calendar->getYesterdayUrl();
			$params['TomorrowUrl']     = $calendar->getTomorrowUrl();
			$params['ModeMonthUrl']    = $calendar->getModeChangeUrl('month');
			$params['ModeWeekUrl']     = $calendar->getModeChangeUrl('week');
			$params['CurrentDay']      = $calendar->getCurrentDayStamp();
		}
		elseif ($type == 'week') {
			// panel navigation
			$params['WeekPrevYearUrl'] = $calendar->getYearUrl('week', 'prev');
			$params['WeekNextYearUrl'] = $calendar->getYearUrl('week', 'next');
			$params['WeekPrevWeekUrl'] = $calendar->getPrevWeekUrl();
			$params['WeekNextWeekUrl'] = $calendar->getNextWeekUrl();
			$params['ModeMonthUrl']    = $calendar->getModeChangeUrl('month');
			$params['ModeDayUrl']      = $calendar->getDayUrl();
			$params['WeekStamp']       = $calendar->getWeekStamp();
		}

		return $params;
	}


    /**
	 * check if date is valid date
	 *
	 * @param      int   $year
	 * @param      int   $month
     * @param      int   $day
	 *
	 * @throws     NotFoundHttpException
	 */
    public function checkInputDate($year, $month, $day) {

        if (!checkdate($month, $day, $year)) {
            throw new NotFoundHttpException("Page not found");
        }
    }

    /**
	 * get calendar template parameters
	 *
	 * @return     array $params
	 */
    public function getCalendarTemplateParams($options = null) {

        // get a new calendar
        $calendar = $this->services->get('timetm.calendar.month');

        // set default year and month
        if (!$options['year']) {
            $options['year']  = date('Y');
            $options['month'] = date('m');
        }

        $addDates = false;

        if (array_key_exists('dates', $options)) {
            $addDates = $options['dates'];
            // remove from $options for calendar init
            unset($options['dates']);
        }

        // initialize the calendar
        $calendar->init($options);

        $params = $this->getBaseTemplateParams($calendar);

        // add month dates
        if ($addDates) {
            $params['days'] = $calendar->getMonthCalendarDates();
        }

        return $params;
    }

}
