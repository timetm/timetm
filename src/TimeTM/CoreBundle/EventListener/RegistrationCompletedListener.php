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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;

use FOS\UserBundle\Event\FilterUserResponseEvent;

use TimeTM\CoreBundle\Entity\Agenda;

/**
 * Listener responsible for adding the default user role at registration
 */
class RegistrationCompletedListener implements EventSubscriberInterface
{
	
	protected $em;
	
	function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationCompleted',
        );
    }

    public function onRegistrationCompleted(FilterUserResponseEvent $event)
    {
    	
    	$agenda = new Agenda();
    	$user = $event->getUser();
    	$agenda->setUser($user);
    	$agenda->setName('default');
    	$agenda->setDescription('default');
    	
    	$this->em->persist($agenda);
    	$this->em->flush();

    }
}