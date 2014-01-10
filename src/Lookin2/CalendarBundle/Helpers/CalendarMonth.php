<?php

// src/Lookin2/CalendarBundle/Helpers/CalendarMonth.php

/**
 * Class to generate month views
 *
 * @author andre@at-info.ch
 * @www.at-info.ch
 */
namespace Lookin2\CalendarBundle\Helpers;

use Symfony\Component\Routing\Router;

class CalendarMonth extends Calendar {

	function __construct(Router $router) {
		parent::__construct($router);
	}

}
