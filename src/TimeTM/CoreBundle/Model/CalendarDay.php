<?php

/**
 * This file is part of TimeTM
 *
 * @author André andre@at-info.ch
 */

// src/TimeTM/CoreBundle\Calendar/Model/CalendarDay.php

/**
 * Class to generate day views
 *
 * @author andre@at-info.ch
 *         @www.at-info.ch
 */
namespace TimeTM\CoreBundle\Model;

use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * class representing a daily calendar
 */
class CalendarDay extends Calendar {
	
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
	 * the current day
	 *
	 * @var string $day
	 */
	private $day;
	
	/**
	 * prevMonthDay
	 *
	 * @var string
	 */
	private $prevMonthDay;
	
	/**
	 * nextMonthDay
	 *
	 * @var string
	 */
	private $nextMonthDay;
	
	/**
	 * yesterdayYear
	 *
	 * @var string
	 */
	private $yesterdayYear;
	
	/**
	 * yesterdayMonth
	 *
	 * @var string
	 */
	private $yesterdayMonth;
	
	/**
	 * yesterdayDay
	 *
	 * @var string
	 */
	private $yesterdayDay;
	
	/**
	 * tomorrowYear
	 *
	 * @var string
	 */
	private $tomorrowYear;
	
	/**
	 * tomorrowMonth
	 *
	 * @var string
	 */
	private $tomorrowMonth;
	
	/**
	 * tomorrowDay
	 *
	 * @var string
	 */
	private $tomorrowDay;
	
	/**
	 * dayName
	 *
	 * @var string
	 */
	private $dayName;
	
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
	 * @param integer $dayStart
	 *        	Configuration parameter
	 * @param integer $dayEnd
	 *        	Configuration parameter
	 */
	public function __construct(Router $router, TranslatorInterface $translator) {
		parent::__construct ( $router, $translator );
	}
	
	/**
	 * Set additionnal panel navigation parameters.
	 *
	 * add the following properties
	 *
	 * - yesterdayDay.
	 * - yesterdayMonth.
	 * - yesterdayYear
	 * - tomorrowDay.
	 * - tomorrowMonth.
	 * - tomorrowYear
	 */
	public function setAdditionnalNavigationParameters() {
		$year = $this->getYear ();
		$month = $this->getMonth ();
		$this->yesterdayDay = date ( 'd', mktime ( 0, 0, 0, $month, $this->day - 1, $year ) );
		$this->yesterdayMonth = date ( 'm', mktime ( 0, 0, 0, $month, $this->day - 1, $year ) );
		$this->yesterdayYear = date ( 'Y', mktime ( 0, 0, 0, $month, $this->day - 1, $year ) );
		$this->tomorrowDay = date ( 'd', mktime ( 0, 0, 0, $month, $this->day + 1, $year ) );
		$this->tomorrowMonth = date ( 'm', mktime ( 0, 0, 0, $month, $this->day + 1, $year ) );
		$this->tomorrowYear = date ( 'Y', mktime ( 0, 0, 0, $month, $this->day + 1, $year ) );
	}
	
	/**
	 * Get YesterdayUrl
	 *
	 * @return string
	 */
	public function getYesterdayUrl() {
		$url = $this->router->generate ( 'day', array (
			'year' => $this->yesterdayYear,
			'month' => $this->yesterdayMonth,
			'day' => $this->yesterdayDay 
		) );
		return $url;
	}
	
	/**
	 * Get YesterdayUrl
	 *
	 * @return string
	 */
	public function getTomorrowUrl() {
		$url = $this->router->generate ( 'day', array (
			'year' => $this->tomorrowYear,
			'month' => $this->tomorrowMonth,
			'day' => $this->tomorrowDay 
		) );
		return $url;
	}
	
	/**
	 * Get day
	 *
	 * @return string
	 */
	public function getDay() {
		return $this->day;
	}
	
	/**
	 * Get dayName
	 *
	 * @return string
	 */
	public function getDayName() {
		return $this->dayName;
	}
	
	/**
	 * Get prevMonthDay
	 *
	 * @return string
	 */
	public function getPrevMonthDay() {
		return $this->prevMonthDay;
	}
	
	/**
	 * Get nextMonthDay
	 *
	 * @return string
	 */
	public function getNextMonthDay() {
		return $this->nextMonthDay;
	}
	
	/**
	 * Get day stamp
	 *
	 * @return string
	 */
	public function getCurrentDayStamp() {
		$translatedMonthName = $this->translator->trans ( $this->getMonthName () );
		$translatedDayName = $this->translator->trans ( $this->dayName );
		return $translatedDayName . ', ' . ( int ) $this->day . ' ' . $translatedMonthName . ' ' . $this->getYear ();
	}
	
	/*
	 * -- protected -------------------------------------------------------------
	 */
	
	/**
	 * Set additionnal panel navigation parameters.
	 *
	 * set :
	 *
	 * - month
	 * - monthName
	 * - day
	 * - dayName
	 * - prevMonthDay
	 * - NextMonthDay
	 *
	 * extends Calender::init
	 *
	 * @see Calender::init() The extended function
	 *     
	 * @param array $options        	
	 */
	protected function childInit(array $options = array()) {
		// set common vars
		$this->setYear ( $options ['year'] );
		$this->setMonth ( $options ['month'] );
		$this->setDay ( $options ['day'] );
		$this->setMonthName ();
		$this->setDayName ();
		$this->setPrevMonthDay ();
		$this->setNextMonthDay ();
		$this->setWeekno ( date ( 'W', mktime ( 0, 0, 0, $this->getMonth (), $this->getDay (), $this->getYear () ) ) );
	}
	
	/*
	 * -- private ---------------------------------------------------------------
	 */
	
	/**
	 * Set day
	 *
	 * @param string $day        	
	 */
	private function setDay($day) {
		// TODO : validation : check if integer, if in month
		if (! $day) {
			$day = date ( 'd' );
		}
		$this->day = $day;
	}
	
	/**
	 * Set dayName
	 */
	private function setDayName() {
		$this->dayName = date ( 'D', mktime ( 0, 0, 0, $this->getMonth (), $this->day, $this->getYear () ) );
		;
	}
	
	/**
	 * Set prevMonthDay
	 */
	private function setPrevMonthDay() {
		$daysInLastMonth = date ( 't', mktime ( 0, 0, 0, $this->getMonth () - 1, 1, $this->getYear () ) );
		if ($this->day > $daysInLastMonth) {
			$this->prevMonthDay = $daysInLastMonth;
		} else {
			$this->prevMonthDay = $this->day;
		}
	}
	
	/**
	 * Set NextMonthDay
	 */
	private function setNextMonthDay() {
		$daysInNextMonth = date ( 't', mktime ( 0, 0, 0, $this->getMonth () + 1, 1, $this->getYear () ) );
		
		if ($this->day > $daysInNextMonth) {
			$this->nextMonthDay = $daysInNextMonth;
		} else {
			$this->nextMonthDay = $this->day;
		}
	}
}
