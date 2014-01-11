<?php

// src/Lookin2/CalendarBundle/Helpers/Calendar.php

/**
 * Class to generate month, week and day views
 *
 * @author andre@at-info.ch
 * @www.at-info.ch
 */
namespace Lookin2\CalendarBundle\Helpers;

use Symfony\Component\Routing\Router;

class Calendar {

  /**
   * @var \Symfony\Component\Routing\Router
   */
	protected $router;

	/**
	 * @var integer $year
	 */
	protected $year;

	/**
	 * @var integer $month
	 */
	protected $month;

	/**
	 * Constructor.
	 *
	 * @param service $router      The router seervice
	 */
	public function __construct(Router $router) {
		$this->router   = $router;
	}

  /**
   * Set year
   *
   * @param string $year
   */
	private function setYear($year) {
		// TODO : validation : check if integer
		if (!$year) { $year  = date('Y'); }
		$this->year = $year;
	}

	/**
	 * Set month
	 *
	 * @param string $month
	 */
	private function setMonth($month) {
		if ( ! $month or $month < 1 or $month > 12 ) {
			$month = date('m');
		}
		$this->month = $month;
	}

	
	/**
	 * Set monthName
	 *
	 * @param string $month
	 */
	private function setMonthName() {
		$this->monthName =  date("F", mktime(0, 0, 0, $this->month));
	}

	/**
	 * Set panel navigation parameters
	 *
	 *   PrevMonthYear
	 *   PrevMonthMonth
	 *   NextMonthMonth
	 *   NextMonthYear
	 */
	private function setPanelNavigationParameters() {
		$this->PrevMonthYear = date('Y', mktime(0, 0, 0, $this->month - 1, 1, $this->year));
		$this->PrevMonthMonth = date('m', mktime(0, 0, 0, $this->month - 1, 1, $this->year));
		$this->NextMonthMonth = date('m', mktime(0, 0, 0, $this->month + 1, 1, $this->year));
		$this->NextMonthYear = date('Y', mktime(0, 0, 0, $this->month + 1, 1, $this->year));
	}
	

	public function globalInit($year, $month) {
		// set common vars
		$this->setYear($year);
		$this->setMonth($month);
		$this->setMonthName();
		// set parameters for url generation 
		$this->setPanelNavigationParameters();
	}

	/**
	 * Get monthName
	 *
	 * @return string
	 */
	public function getMonthName() {
		return $this->monthName;
	}
	
	/**
	 *  -- Getters --------------------------------------------------------------
	 */

  /**
   * Get month
   *
   * @return string 
   */
	public function getMonth() {
		return $this->month;
	}
	
  /**
   * Get year
   *
   * @return string 
   */
	public function getYear() {
		return $this->year;
	}


  /**
   * Get monthStamp
   *
   * @return string 
   */
	public function getCurrentMonthStamp() {
		return $this->monthName . ' ' . $this->year;;
	}

  /**
   * Get PrevYearUrl
   *
   * @return string 
   */
	public function getPrevYearUrl() {
		$url = $this->router->generate('month', array(
				'year'  => $this->year - 1 ,
				'month' => $this->month )
		);
		return $url;
	}

  /**
   * Get PrevMonthUrl
   *
   * @return string 
   */
	public function getPrevMonthUrl() {
		$url = $this->router->generate('month', array(
  		'year'  => $this->PrevMonthYear ,
			'month' => $this->PrevMonthMonth
		));
		return $url;
	}

  /**
   * Get NextMonthUrl
   *
   * @return string 
   */
	public function getNextMonthUrl() {
		$url = $this->router->generate('month', array(
				'year'  => $this->NextMonthYear ,
				'month' => $this->NextMonthMonth
		));
		return $url;
	}
	
  /**
   * Get NextYearUrl
   *
   * @return string 
   */
	public function getNextYearUrl() {
		$url = $this->router->generate('month', array(
				'year'  => $this->year + 1 ,
				'month' => $this->month )
		);
		return $url;
	}

  /**
   * Get DayhUrl
   *
   * @param string $view 
   *
   * @param string $day
   *
   * @return string 
   */
	public function getDayUrl($view, $day) {
		
		switch ($view) {
			case 'day':
				$url = $this->router->generate($view, array(
						'year'  => $this->year ,
						'month' => $this->month ,
						'day'   => $day
				));
				break;
			case 'month':
				$url = $this->router->generate($view, array(
						'year'  => $this->year ,
						'month' => $this->month ,
				));
				break;
		}

		return $url;
	}
	

	/**
	 * get the dates to display for a monthly view
	 * 
	 * @param string $view					The view displayed : Month, Day, Week
	 * 
	 * @return array $monthDates    A list of dates
	 */
	public function getMonthCalendarDates($view) {
		
		$monthDates = array();
	
		$currentDayOfWeek = date('N', mktime(0, 0, 0, $this->month,     1, $this->year)) - 1;
		$daysInMonth =      date('t', mktime(0, 0, 0, $this->month,     1, $this->year));
		$daysInLastMonth =  date('t', mktime(0, 0, 0, $this->month - 1, 1, $this->year));
	
		// -- PREVIOUS MONTH --------------------------------------------------------
		
		for($x = 0; $x < $currentDayOfWeek; $x++) {
			$dayNum = (($daysInLastMonth - ($currentDayOfWeek - 1)) + $x);
			array_push($monthDates, array ( 
				'day' => $dayNum,
				'id'  => $this->getPrevMonthUrl() // TODO : move outside loop
			));
		};
	
		// -- CURRENT MONTH ---------------------------------------------------------
		for($dayNum = 1; $dayNum <= $daysInMonth; $dayNum++) {
			$dayLink = ( $dayNum > 9 ) ? $dayNum : '0'.$dayNum;
			array_push($monthDates, array ( 
				'day' => $dayNum, 
				'id'  => $this->getDayUrl($view,$dayLink),
				''
			));
			$currentDayOfWeek++;
			if($currentDayOfWeek == 7) {
				$currentDayOfWeek = 0;
			};
		};
	
		// -- NEXT MONTH ------------------------------------------------------------
		if ($currentDayOfWeek < 7 && $currentDayOfWeek != 0) {
			for ($dayNum = 1; $dayNum <= (7 - $currentDayOfWeek); $dayNum++) {
				$dayLink = ( $dayNum < 10 ) ? '0'.$dayNum : $dayNum;
				array_push($monthDates, array ( 
					'day' => $dayNum, 
					'id'  => $this->getNextMonthUrl() // TODO : move outside loop
				));
			};
		};
	
		return $monthDates;
	}

}
