<?php

/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\CoreBundle\Model;

use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class representing a weekly calendar
 * 
 * @author André Friedli <a@frian.org>
 */
class CalendarWeek extends Calendar {
	
	/**
	 * the router service
	 *
	 * @var \Symfony\Component\Routing\Router
	 */
	protected $router;
	
	/**
	 * the translator service
	 *
	 * @var \Symfony\Component\Translation\Translator
	 */
	protected $translator;
	
	/**
	 * month number for the current week
	 *
	 * @var string
	 */
	protected $weekMonth;

	/*
	 * -- public ----------------------------------------------------------------
	 */
	
	/**
	 * Constructor.
	 *
	 * @param service $router
	 *        	The router service
	 * @param service $translator
	 *        	The translation service
	 */
	public function __construct(Router $router, TranslatorInterface $translator) {
		parent::__construct ($router, $translator);
	}
	
	/**
	 * get the dates to display for a weekly view
	 *
	 * @return array $weekDates A list of dates
	 *        
	 */
	public function getWeekCalendarDates() {
		$weekDates = array ();

		for($i = 1; $i < 8; $i ++) {
			$timestamp = strtotime($this->getYear() . '-W' . $this->getWeekno()  . '-' . $i);
			$datestamp = date('Y/m/d', $timestamp);
			$dayName = $this->translator->trans(date('D', $timestamp));
			$date = \explode('/', $datestamp);
			$daystamp = $dayName . ", " . $date[2] . " " . $this->getMonthNameFromMonthNumber($date[1]) . " " . $date[0];
	
			$url = $this->router->generate ('day', array(
					'year' => $date[0],
					'month' => $date[1],
					'day' => $date[2]
			));

			array_push($weekDates, array(
				'datestamp' => $datestamp,
				'daystamp' => $daystamp,
				'url' => $url
			));
		}

		return $weekDates;
	}
	
	/**
	 * Get NextWeekUrl
	 *
	 * @return string $url
	 */
	public function getNextWeekUrl() {
		$weekInYear = date("W", mktime(0, 0, 0, 12, 28, $this->getYear()));
		
		$nextWeekNo = $this->getWeekno() + 1;
		$nextWeekYear = $this->getYear();
		
		if ($nextWeekNo > $weekInYear) {
			$nextWeekNo = '01';
			$nextWeekYear ++;
		}
		
		$url = $this->router->generate('week', array(
			'year' => $nextWeekYear,
			'weekno' => $nextWeekNo 
		));
		
		return $url;
	}
	
	/**
	 * Get PrevWeekUrl
	 *
	 * @return string $url
	 */
	public function getPrevWeekUrl() {
		$prevWeekNo = $this->getWeekno() - 1;
		$prevWeekYear = $this->getYear();
		
		if ($prevWeekNo < 1) {
			$prevWeekYear --;
			$prevWeekNo = date("W", mktime( 0, 0, 0, 12, 28, $prevWeekYear));
		}
		
		$url = $this->router->generate('week', array (
			'year' => $prevWeekYear,
			'weekno' => $prevWeekNo 
		));
		
		return $url;
	}
	
	/**
	 * Get WeekStamp
	 *
	 * @return string $url
	 */
	public function getWeekStamp() {
		
		// day number
		$lastDayNumOfWeek = (int)$this->getLastDateOfWeek('d');
		
		// month numbers
		$firstDayMonthNum = $this->getFirstDateOfWeek('m');
		$lastDayMonthNum = $this->getLastDateOfWeek('m');
		
		// years
		$firstDayYear = (int)$this->getFirstDateOfWeek('Y');
		$lastDayYear = (int)$this->getLastDateOfWeek ('Y');
		
		// month names
		$firstDayMonthName = $this->getMonthNameFromMonthNumber($firstDayMonthNum);
		$lastDayMonthName = $this->getMonthNameFromMonthNumber($lastDayMonthNum);
		
		$weekStamp = '';
		
		$weekStamp .= (int)$this->getWeekno() . ', ' . (int)$this->getFirstDateOfWeek('d') . ' ';
		
		// if the week is in one month
		if ($firstDayMonthNum == $lastDayMonthNum) {
			$weekStamp .= ' - ' . $lastDayNumOfWeek . ' ' . $firstDayMonthName . ' ' . $this->getYear();
		}
		// if we are in one year
		elseif ($firstDayYear == $lastDayYear) {
			$weekStamp .= $firstDayMonthName . ' - ' . $lastDayNumOfWeek . ' ' . $lastDayMonthName . ' ' . $this->getYear();
		}
		// if we span 2 years
		else {
			$weekStamp .= $firstDayMonthName . ' ' . $firstDayYear . ' - ' . $lastDayNumOfWeek . ' ' . $lastDayMonthName . ' ' . $lastDayYear;
		}
		return $weekStamp;
	}
	
	/*
	 * -- protected -------------------------------------------------------------
	 */
	
	/**
	 * Set month
	 */
	protected function setWeekMonth() {
		$weekMonthes = array();
		
		for($i = 1; $i < 8; $i ++) {
			array_push($weekMonthes, date('m', strtotime($this->getYear() . '-W' . $this->getWeekno() . '-' . $i)));
		}
		
		$buffer = array_count_values($weekMonthes);
		
		$currentCount = 0;
		$currentMonth = null;
		foreach ( $buffer as $month => $count ) {
			if ($count > $currentCount) {
				$currentCount = $count;
				$currentMonth = $month;
			}
		}
		$this->setMonth($currentMonth);
	}
	
	/**
	 * initialize the calendar.
	 *
	 * set :
	 *
	 * - year
	 * - weekno
	 *
	 * extends Calender::init
	 * 
	 * @see Calender::init() The extended function
	 *     
	 * @param mixed $param        	
	 */
	protected function childInit(array $options = array()) {
		
		// set common vars
		$this->setYear($options ['year']);
		$this->setWeekno($options ['weekno']);
		$this->setWeekMonth();
	}
	
	/**
	 * Set additionnal panel navigation parameters
	 */
	protected function setAdditionnalNavigationParameters() {}
	
	/*
	 * -- private ---------------------------------------------------------------
	 */
	
	/**
	 * Get MonthNameFromMonthNumber
	 *
	 * @param integer $monthNumber        	
	 *
	 * @return string
	 */
	private function getMonthNameFromMonthNumber($monthNumber) {
		$monthName = date("M", mktime (0, 0, 0, $monthNumber));
		return $this->translator->trans($monthName);
	}
	
	/**
	 * Get FirstDateOfWeek
	 *
	 * @param string $format
	 *        	PHP date format
	 * @return string
	 */
	public function getFirstDateOfWeek($format) {
		return date( $format, strtotime($this->getYear() . '-W' . $this->getWeekno() . '-1'));
	}
	
	/**
	 * Get LastDateOfWeek
	 *
	 * @param string $format
	 *        	PHP date format
	 * @return string
	 */
	public function getLastDateOfWeek($format) {
		return date( $format, strtotime($this->getYear() . '-W' . $this->getWeekno() . '-7'));
	}
}

