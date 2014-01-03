<?php

namespace Lookin2\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{	
	/**
	 * @Route("/")
	 * @Template()
	 */
	public function indexAction()
	{
		return array('msg' => 'app root');
	}

}
