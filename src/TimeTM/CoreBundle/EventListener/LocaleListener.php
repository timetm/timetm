<?php

namespace TimeTM\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleListener implements EventSubscriberInterface {

    /**
	 * Default locale
	 *
	 * @var srting $defaultLocale
	 */
    private $defaultLocale;

    /**
	 * Constructor.
	 *
	 * @param string $defaultLocale
	 */
    public function __construct($defaultLocale = 'en') {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * Update Locale in session
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event) {

        $request = $event->getRequest();

        if (!$request->hasPreviousSession()) {
            return;
        }

        $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
    }

    /**
	 * Get KernelEvents::REQUEST event
	 *
	 * @return array event
	 */
    public static function getSubscribedEvents() {

        return array(
            // must be registered after the default Locale listener (15)
            KernelEvents::REQUEST => array(array('onKernelRequest', 15)),
        );
    }
}
