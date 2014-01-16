<?php
/**
 * This file is part of Lookin2
 *
 * @author André andre@at-info.ch
 */

// src/Lookin2/CalendarBundle/Helpers/CalendarWeek.php

namespace Lookin2\CalendarBundle\Helpers;

use Symfony\Component\Routing\Router;

/**
 * class representing a weekly calendar
 */
class CalendarWeek extends Calendar {

	/**
	 * Constructor.
	 *
	 * @param   service   $router   The router service
	 */
	function __construct(Router $router) {
		parent::__construct($router);
	}


}
