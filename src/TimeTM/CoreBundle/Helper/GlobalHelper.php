<?php
/**
 * This file is part of TimeTM
 *
 * @author André andre@at-info.ch
 */


namespace TimeTM\CoreBundle\Helper;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
    public function __construct(\Doctrine\ORM\EntityManager $em, $securityContext, $container, $twig) {

 		$this->em = $em;
 		$this->context = $securityContext;
        $this->container = $container;
        $this->twig = $twig;
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

    /**
     * get user agenda switch select form
     *
     * @return string select form
     */
     public function getUserAgendaSwitchForm() {

         $formFactory = $this->container->get('form.factory');

         $form = $formFactory->create()
             ->add('agenda', ChoiceType::class, array(
                 'choices'  => array(
                     'Maybe' => null,
                     'Yes' => true,
                     'No' => false,
                 ),
             )
         );

         $params = array( 'form' => $form->createView() );
         return $this->twig->render( 'TimeTMCoreBundle:Default:calendarSwitch.html.twig', $params );
     }
}
