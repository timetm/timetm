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

    /**
     * get user agenda switch select form
     *
     * @return string select form
     */
     public function getUserAgendaSwitchForm() {

         // get user agendas
         $user = $this->context->getToken()->getUser();
         $agendas = $user->getAgendas();

         // create parameters array
         $choices = array();
         foreach ($agendas as $key => $agenda) {
             $choices[$agenda->getName()] = $agenda->getId();
         }

         // get current agenda
         $agenda = $this->session->get('ttm/agenda/current');

         // create form
         $form = $this->container->get('form.factory')->create()
             ->add('agenda', ChoiceType::class, array(
                 'choices'  => $choices,
                 'data'     => $agenda
             )
         );

         $params = array( 'form' => $form->createView() );
         return $this->twig->render( 'TimeTMCoreBundle:Default:calendarSwitch.html.twig', $params );
     }
}
