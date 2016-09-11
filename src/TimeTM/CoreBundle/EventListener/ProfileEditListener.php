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
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Listener responsible for adding the default user role at registration
 */
class ProfileEditListener implements EventSubscriberInterface
{

	/**
	 * Get FOSUserEvents::PROFILE_EDIT_SUCCESS event
	 *
	 * @return array
	 */
    public static function getSubscribedEvents() {

        return array(
            FOSUserEvents::PROFILE_EDIT_SUCCESS => 'onEditCompleted',
        );
    }

    /**
     * Update language in session
     *
     * @param FormEvent $event
     */
    public function onEditCompleted(FormEvent $event) {

        $request = $event->getRequest();
        $session = $request->getSession();
        $form = $event->getForm();
        $user = $form->getData();
        $lang = $user->getLanguage()->getName();

        $session->set('_locale', $lang);
        $request->setLocale($lang);
    }
}
