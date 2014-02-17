<?php
/**
 * This file is part of Lookin2
 *
 * @author André andre@at-info.ch
 */

// src/Lookin2/CalendarBundle/Model/CalendarWeek.php

namespace Lookin2\CalendarBundle\Model;

use Symfony\Component\Routing\Router;

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
	 * Constructor.
	 *
	 * @param   service   $router   The router service
	 */
	public function __construct(Router $router) {
		parent::__construct($router);
	}


}
