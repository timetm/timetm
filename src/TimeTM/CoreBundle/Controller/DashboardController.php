<?php
/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Dashboard controller.
 *
 * @author André Friedli <a@frian.org>
 */
class DashboardController extends Controller {

	/**
	 * Empty home page
	 *
	 * @Route("/", name="dashboard")
	 * @Method("GET")
	 */
	public function indexAction(Request $request) {

        $request->getSession()->set('ttm/event/referer', $request->getRequestUri());

		// get events
		list($events, $days) = $this->get('timetm.event.helper')->getDashboardEvents();


        // get tasks
        $taskDays = $this->getParameter('timetm.dashboard.task.days');
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('TimeTMCoreBundle:Task')->findActiveInNextDays($taskDays);


		// set params
        $params = array(
            'events'    => $events,
            'eventdays' => $days,
            'tasks'     => $tasks,
            'template'  => 'index'
        );

        // ajax detection
        if ($request->isXmlHttpRequest()) {
        	$params['buttonText'] = 'action.close';
        	return $this->render( 'TimeTMCoreBundle:Dashboard:index.html.twig', $params );
        }

		// get a new calendar
		$calendar = $this->get('timetm.calendar.month');

		// initialize the calendar
		$calendar->init( array(
			'year' => date('Y'),
			'month' => date('m')
		));

		// get common template params
		$params = \array_merge($params,$this->get('timetm.calendar.helper')->getBaseTemplateParams($calendar));

		return $this->render( 'TimeTMCoreBundle:Dashboard:dashboard.html.twig', $params );
	}


    /**
     * get user agenda switch select form
     *
     * @return string select form
     */
     public function getUserAgendaSwitchFormAction(Request $request) {

         // get user agendas
         $user = $this->getUser();
         $agendas = $user->getAgendas();

         // create parameters array
         $choices = array();
         foreach ($agendas as $key => $agenda) {
             $choices[$agenda->getName()] = $agenda->getId();
         }

         // get current agenda
         $agenda = $request->getSession()->get('ttm/agenda/current');

         // create form
         $form = $this->get('form.factory')->create()
             ->add('agenda', ChoiceType::class, array(
                 'choices'  => $choices,
                 'data'     => $agenda,
                 'choice_translation_domain' => false,
             )
         );

         $params = array( 'form' => $form->createView() );
         return $this->render( 'TimeTMCoreBundle:Default:calendarSwitch.html.twig', $params );
     }


     /**
      * Switch between agenda.
      *
      * @Route("/agenda/switch", name="agenda_switch")
      * @Method("POST")
      */
     public function agendaSwitchAction(Request $request) {

         $request->getSession()->set('ttm/agenda/current', $request->request->get('form')['agenda']);

         $response['success'] = true;
         $response['referer'] = $request->headers->get('referer');

         return new JsonResponse( $response );
     }
}
