<?php

// src/Lookin2/CalendarBundle/Helpers/CalendarWeek.php

/**
 * Class to generate week views
 *
 * @author andre@at-info.ch
 * @www.at-info.ch
 */
namespace Lookin2\CalendarBundle\Helpers;

use Symfony\Component\Routing\Router;

class CalendarWeek extends Calendar {
	
	function __construct(Router $router) {
		parent::__construct($router);
	}

	
}
