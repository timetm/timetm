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

namespace TimeTM\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use TimeTM\CoreBundle\Entity\Event;
use TimeTM\CoreBundle\Form\Type\EventType;

/**
 * Event controller.
 *
 * @Route("/event")
 *
 * @author André Friedli <a@frian.org>
 */
class EventController extends Controller
{

    /**
     * Lists all Event entities.
     *
     * @Route("/", name="event")
     * @Method("GET")
     */
    public function indexAction(Request $request) {

        // store the route in session (referer for event add)
		$request->getSession()->set('ttm/event/referer', $request->getRequestUri());

        $em = $this->getDoctrine()->getManager();

        $this->getUser()->getId();

        $entities = $em->getRepository('TimeTMCoreBundle:Event')->findAllByUser($this->getUser()->getId());

        $params = array(
            'entities' => $entities,
            'template' => 'index',
            'buttonText' => 'action.back.list'
        );

        // ajax detection
        if ($request->isXmlHttpRequest()) {
        	$params['buttonText'] = 'action.close';
        	return $this->render( 'TimeTMCoreBundle:Event:index.html.twig', $params );
        }

        // add common template params
        $params = \array_merge($params, $this->get('timetm.calendar.helper')->getCalendarTemplateParams());

        return $this->render('TimeTMCoreBundle:Event:event.html.twig', $params);
    }

    /**
     * Creates a new Event entity.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("/", name="event_create")
     * @Method("POST")
     */
    public function createAction(Request $request) {

        $event = new Event();

        $form = $this->createCreateForm($event, $request);
        $form->handleRequest($request);

        // -- create parameters array
        $params = array(
            'entity'     => $event,
            'form'       => $form->createView(),
            'template'   => 'new',
            'buttonText' =>'action.back.list'
        );

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $this->get('timetm.event.helper')->setEventDuration($event);

            $em->persist($event);
            $em->flush();

            if ($request->isXmlHttpRequest()) {

            	$response['success'] = true;
            	$response['referer'] = $request->getSession()->get('ttm/event/referer');

            	return new JsonResponse( $response );
            }

            return $this->redirect($request->getSession()->get('ttm/event/referer'));
        }
        else {
        	if ( $request->isXmlHttpRequest()) {

			    // -- set button text
                $params['buttonText'] = 'action.close';

			    return $this->render( 'TimeTMCoreBundle:Event:ajax.html.twig', $params );
		    }
        }

        // get common template params
        $params = \array_merge($params,
            $this->get('timetm.calendar.helper')->getCalendarTemplateParams(array(
                'year'  => $event->getStartdate()->format('Y'),
                'month' => $event->getStartdate()->format('m'),
                'dates' => true
            )));

        return $this->render('TimeTMCoreBundle:Event:event.html.twig', $params);
    }

    /**
    * Creates a form to create a Event entity.
    *
    * @param Event $event
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Event $event, Request $request) {

        $form = $this->createForm(EventType::class, $event, array(
            'action'         => $this->generateUrl('event_create'),
            'method'         => 'POST',
            'entity_manager' => $this->get('doctrine.orm.entity_manager'),
            'user'           => $this->getUser()->getId(),
            'contactHelper'  => $this->get('timetm.contact.helper'),
            'currentAgenda'  => $request->getSession()->get('ttm/agenda/current')
        ));

        $form->add('save', SubmitType::class, array('label' => 'action.save'));

        return $form;
    }

    /**
     * Displays a form to create a new Event entity.
     *
     * @param      int       $year
     * @param      int       $month
     * @param      int       $day
     * @param      int       $hour
     * @param      int       $min
     *
     * @Route("/new", name="event_new")
     * @Route("/new/{year}/{month}/{day}", name="event_new_day")
     * @Route("/new/{year}/{month}/{day}/{hour}/{min}", name="event_new_time")
     *
     * @Method("GET")
     */
    public function newAction(Request $request, $year = null, $month = null, $day = null, $hour = null, $min = null) {

    	// pre-fill event
        $event = $this->get('timetm.event.helper')->fillNewEvent($year, $month, $day, $hour, $min);

        // create form
        $form = $this->createCreateForm($event, $request);

        // -- add template params
		$params = array(
            'entity'   => $event,
            'form'     => $form->createView(),
            'template' => 'new',
            'buttonText' => 'action.back.list'
        );

        // ajax detection
        if ($request->isXmlHttpRequest()) {
        	$params['buttonText'] = 'action.close';
        	return $this->render( 'TimeTMCoreBundle:Event:ajax.html.twig', $params );
        }

        // add common template params
        $params = \array_merge($params, $this->get('timetm.calendar.helper')->getCalendarTemplateParams($year, $month));

        // no ajax
        return $this->render( 'TimeTMCoreBundle:Event:event.html.twig', $params );
    }

    /**
     * Finds and displays a Event entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="event_show")
     * @Method("GET")
     */
    public function showAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('TimeTMCoreBundle:Event')->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        // -- add template params
        $params = array(
            'entity'      => $event,
            'delete_form' => $deleteForm->createView(),
            'template'    => 'show',
            'buttonText' => 'action.back.list'
        );

        // ajax detection
        if ($request->isXmlHttpRequest()) {
            $params['buttonText'] = 'close';
        	return $this->render( 'TimeTMCoreBundle:Event:ajax.html.twig', $params );
        }

        // add common template params
        $params = \array_merge($params,
            $this->get('timetm.calendar.helper')->getCalendarTemplateParams(array(
                'year'  => $event->getStartdate()->format('Y'),
                'month' =>$event->getStartdate()->format('m')
            )));

        return $this->render('TimeTMCoreBundle:Event:event.html.twig', $params);
    }

    /**
     * Displays a form to edit an existing Event entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="event_edit")
     * @Method("GET")
     */
    public function editAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('TimeTMCoreBundle:Event')->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $editForm = $this->createEditForm($event, $request);
        $deleteForm = $this->createDeleteForm($id);

        // -- add template params
        $params = array(
            'entity'      => $event,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'template'    => 'edit',
            'buttonText' => 'action.back.list'
        );

        // ajax detection
        if ($request->isXmlHttpRequest()) {
            $params['buttonText'] = 'close';
        	return $this->render( 'TimeTMCoreBundle:Event:ajax.html.twig', $params );
        }

        // add common template params
        $params = \array_merge($params,
            $this->get('timetm.calendar.helper')->getCalendarTemplateParams(array(
                'year'  => $event->getStartdate()->format('Y'),
                'month' =>$event->getStartdate()->format('m')
            )));

        return $this->render('TimeTMCoreBundle:Event:event.html.twig', $params);
    }

    /**
    * Creates a form to edit a Event entity.
    *
    * @param Event $event The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Event $event, Request $request) {

        $form = $this->createForm(EventType::class, $event, array(
            'action'         => $this->generateUrl('event_update', array('id' => $event->getId())),
            'method'         => 'PUT',
            'entity_manager' => $this->get('doctrine.orm.entity_manager'),
            'user'           => $this->getUser()->getId(),
            'contactHelper'  => $this->get('timetm.contact.helper'),
            'currentAgenda'  => null
        ));

        $form->add('save', SubmitType::class, array('label' => 'action.update'));

        return $form;
    }

    /**
     * Edits an existing Event entity.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="event_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('TimeTMCoreBundle:Event')->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($event, $request);
        $editForm->handleRequest($request);

        // -- create parameters array
        $params = array(
            'entity'     => $event,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'template'   => 'edit',
            'buttonText' =>'action.back.list'
        );

        if ($editForm->isValid()) {

            $this->get('timetm.event.helper')->setEventDuration($event);

            $em->flush();

            if ($request->isXmlHttpRequest()) {

            	$response['success'] = true;
            	$response['referer'] = $request->getSession()->get('ttm/event/referer');

            	return new JsonResponse( $response );
            }

            return $this->redirect($this->generateUrl('event_show', array('id' => $id)));
        }
        else {
        	if ( $request->isXmlHttpRequest()) {

			    // -- set button text
                $params['buttonText'] = 'action.close';

			    return $this->render( 'TimeTMCoreBundle:Event:ajax.html.twig', $params );
		    }
        }

        // get common template params
        $params = \array_merge($params,
            $this->get('timetm.calendar.helper')->getCalendarTemplateParams(array(
                'year'  => $event->getStartdate()->format('Y'),
                'month' => $event->getStartdate()->format('m'),
            )));

        return $this->render('TimeTMCoreBundle:Event:event.html.twig', $params);
    }

    /**
     * Deletes a Event entity.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="event_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {

        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $event = $em->getRepository('TimeTMCoreBundle:Event')->find($id);

            if (!$event) {
                throw $this->createNotFoundException('Unable to find Event entity.');
            }

            $em->remove($event);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('event'));
    }

    /**
     * Creates a form to delete a Event entity by id.
     *
     * @param integer $id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('event_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
