<?php
/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace TimeTM\CoreBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;

use TimeTM\CoreBundle\Entity\Agenda;

/**
 * Listener responsible for adding the default user role at registration
 */
class RegistrationCompletedListener implements EventSubscriberInterface
{
	/**
	 * Entity Manager
	 *
	 * @var EntityManager $em
	 */
	protected $em;

	/**
	 * Constructor.
	 *
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	/**
	 * Get event
	 *
	 * @return array
	 */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationCompleted',
        );
    }

    /**
     * Add default agenda to new user
     *
     * @param FilterUserResponseEvent $event
     */
    public function onRegistrationCompleted(FilterUserResponseEvent $event)
    {

        $theme = $this->em->getRepository('TimeTMCoreBundle:Theme')->find(1);
        $language = $this->em->getRepository('TimeTMCoreBundle:Language')->find(0);

    	$agenda = new Agenda();
    	$user = $event->getUser();
    	$agenda->setUser($user);
    	$agenda->setName('default');
    	$agenda->setDescription('default');

        $user->setTheme($theme);
        $user->setLanguage($language);

    	$this->em->persist($agenda);
    	$this->em->flush();

    }
}
