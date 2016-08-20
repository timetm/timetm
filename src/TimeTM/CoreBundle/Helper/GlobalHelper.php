<?php
/**
 * This file is part of TimeTM
 *
 * @author André andre@at-info.ch
 */


namespace TimeTM\CoreBundle\Helper;

/**
 * class representing a weekly calendar
 */
class GlobalHelper {

    /**
	 * Entity Manager
	 *
	 * @var EntityManager $em
	 */
	protected $em;

	/**
	 * Constructor
	 *
	 * @param EntityManager $em
	 */
    public function __construct(\Doctrine\ORM\EntityManager $em, $securityContext) {

 		$this->em = $em;
 		$this->context = $securityContext;
 	}


    public function getTheme() {

        $user = $this->context->getToken()->getUser();

        $theme = 'green';

        return $theme;
    }

}
