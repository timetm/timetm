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
    public function __construct(\Doctrine\ORM\EntityManager $em, $securityContext, $container, $twig, $session) {

 		$this->em = $em;
 		$this->context = $securityContext;
        $this->container = $container;
        $this->twig = $twig;
        $this->session = $session;
 	}

    /**
     * get user theme
     *
     * @return string $theme
     */
    public function getTheme() {

        $user = $this->context->getToken()->getUser();

        $theme = 'theme-black';

        if ($user !==  'anon.') {
            $theme = $user->getTheme();
        }

        return $theme;
    }
}
