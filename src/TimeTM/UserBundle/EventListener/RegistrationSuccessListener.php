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

namespace TimeTM\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;

use TimeTM\AgendaBundle\Entity\Agenda;

/**
 * Listener responsible for adding the default user role at registration
 */
class RegistrationSuccessListener implements EventSubscriberInterface
{
	
	protected $em;
	
	function __construct(EntityManager $em, ContainerInterface $container)
	{
		$this->em = $em;
		$this->container = $container;
	}

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
    	
//     	$user = $event->getUser();
    	
//     	$lastname = $user->getLast
    	
//     	$agenda = new Agenda();
//     	$user = $event->getUser();
//     	$userId = $user->getId();
//     	$agenda->setUser($user);
//     	$agenda->setName('default');
//     	$agenda->setDescription('default');
    	
//     	$this->em->persist($agenda);
//     	$this->em->flush();

    }
}