<?php
/**
 * This file is part of Lookin2
 *
 * @author André andre@at-info.ch
 */

// src/Lookin2/CalendarBundle/Model/CalendarWeek.php

namespace Lookin2\CalendarBundle\Model;

use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\Translator;

/**
 * class representing a weekly calendar
 */
class CalendarWeek extends Calendar {

	/**
	 * the router service
	 *
	 * @var     \Symfony\Component\Routing\Router
	 */
	protected $router;

	/**
	 * the translator service
	 *
	 * @var     \Symfony\Component\Translation\Translator
	 */
	protected $translator;

	/**
	 * the week number
	 *
	 * @var     string
	 */
	protected $weekno;

	/**
	 * month number
	 *
	 * @var     string
	 */
	protected $month;
	
	/**
	 * format 
	 *
	 * @var     string
	 */
	private $format;

	/**
	 * options
	 *
	 * @var     array
	 */
	private $options;
	
	/**
	 * Constructor.
	 *
	 * @param   service   $router        The router service
	 * @param   service   $translator    The translation service
	 */
	public function __construct(Router $router, Translator $translator) {
		parent::__construct($router, $translator);
	}

	/**
	 * Set weekno
	 *
	 * @param   string    $weekno
	 */
	private function setWeekno($weekno) {
		// TODO : validation : check if integer, if in month
		if (!$weekno) {
			$weekno  = date('W');
		}
		$this->weekno = $weekno;
	}


	/**
	 * Get weekno
	 *
	 * @return  string
	 */
	public function getWeekno() {
		return $this->weekno;
	}

	
	/**
	 * Set month
	 *
	 */
	protected function setWeekMonth() {
	
		$weekMonthes = array();
	
		for ( $i = 1; $i < 8; $i++ ) {
			array_push($weekMonthes, date('m', strtotime($this->year . '-W' . $this->weekno . '-' . $i )));
		}

		$buffer = array_count_values($weekMonthes);

		$currentCount = 0;
		$currentMonth = null;
		foreach ( $buffer as $month => $count ) {
			
			if ( $count > $currentCount ) {
				$currentCount = $count;
				$currentMonth = $month;
			}
		}

		$this->month = $currentMonth;

	}


	/**
	 * initialize the calendar.
	 *
	 * set :
	 *
	 * - month
	 * - monthName
	 *
	 * extends Calender::init
	 * @see Calender::init()        The extended function
	 *
	 * @param   mixed     $param
	 */
	public function childInit(array $options = array()) {
		
		// set common vars
		$this->setYear($options['year']);
		$this->setWeekno($options['weekno']);
		$this->setWeekMonth();
		
	}


	/**
	 * Set additionnal panel navigation parameters
	 */
	public function setAdditionnalNavigationParameters() {
		// dummy;
	}


	/**
	 * get the dates to display for a weekly view
	 *
	 * @return  array     $weekDates    A list of dates
	 *
	 */
	public function getWeekCalendarDates() {

		$weekDates = array();

		for ( $i = 1; $i < 8; $i++ ) {
			array_push($weekDates, date('Y-m-d', strtotime($this->year . '-W' . $this->weekno . '-' . $i )));
		}

		return $weekDates;
	}

	
	/**
	 * Get MonthNameFromMonthNumber
	 *
	 * @return  string
	 */
	public function getMonthNameFromMonthNumber($month) {
		
		$monthName = date("M", mktime(0, 0, 0, $month));
		return $this->translator->trans($monthName);
	}
	
	
	/**
	 * Get WeekStamp
	 *
	 * @return  string    $url
	 */
	public function getWeekStamp() {

		// day number
		$lastDayNumOfWeek  = (int)$this->getLastDateOfWeek('d');
		
		// month numbers
		$firstDayMonthNum  = $this->getFirstDateOfWeek('m');
		$lastDayMonthNum   = $this->getLastDateOfWeek('m');
		
		//years
		$firstDayYear = (int)$this->getFirstDateOfWeek('Y');
		$lastDayYear  = (int)$this->getLastDateOfWeek('Y');
		
		// month names
		$firstDayMonthName = $this->getMonthNameFromMonthNumber($firstDayMonthNum);
		$lastDayMonthName  = $this->getMonthNameFromMonthNumber($lastDayMonthNum);
		
    $weekStamp = '';
		
    $weekStamp .= 
      (int)$this->getWeekno() . ', ' . 
			(int)$this->getFirstDateOfWeek('d') . ' ';
    
		// if the week is in one month
		if ( $firstDayMonthNum == $lastDayMonthNum ) {

			$weekStamp .= ' - ' .
  			     $lastDayNumOfWeek . ' ' .
				     $firstDayMonthName . ' ' .
			       $this->year;
		}
		// if we are in one year 
		elseif ( $firstDayYear == $lastDayYear ) {

			$weekStamp .= 
			       $firstDayMonthName . ' - ' .
			       $lastDayNumOfWeek . ' ' .
			       $lastDayMonthName . ' ' .
			       $this->year;
		}
		// we span 2 years
		else {
			$weekStamp .= 
	     		   $firstDayMonthName . ' ' .
	     	     $firstDayYear . ' - ' .
			       $lastDayNumOfWeek . ' ' .
			       $lastDayMonthName . ' ' .
			       $lastDayYear;
		}

		return $weekStamp;
	}


	/**
	 * Get FirstDateOfWeek
	 *
	 * @param   string    $format   PHP date format
	 * @return  string
	 */
	public function getFirstDateOfWeek($format) {
		
		return date($format, strtotime($this->year . '-W' . $this->weekno . '-1' ));
	}

	/**
	 * Get LastDateOfWeek
	 *
	 * @param   string    $format   PHP date format
	 * @return  string
	 */
	public function getLastDateOfWeek($format) {
	
		return date($format, strtotime($this->year . '-W' . $this->weekno . '-7' ));
	}
	

}
