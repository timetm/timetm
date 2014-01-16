<?php
/**
 * This file is part of Lookin2
 *
 * @author André andre@at-info.ch
 */

// src/Lookin2/CalendarBundle/Model/CalendarMonth.php

namespace Lookin2\CalendarBundle\Model;

use Symfony\Component\Routing\Router;

/**
 * class representing a monthly calendar
 */
class CalendarMonth extends Calendar {

	/**
	 * Constructor.
	 *
	 * @param   service   $router   The router service
	 */
	public function __construct(Router $router) {
		parent::__construct($router);
	}

	/**
	 * Set additionnal panel navigation parameters
	 */
	public function setAdditionnalNavigationParameters() {
		// dummy;
	}

	/**
	 * Set additionnal panel navigation parameters.
	 * 
	 * extends Calender::init
	 * @see Calender::init()        The extended function
	 * 
	 * @param   mixed     $param    
	 */
	public function childInit(array $options = array()) {
		$this->setMonth($options['month']);
		$this->setMonthName();
	}
}
