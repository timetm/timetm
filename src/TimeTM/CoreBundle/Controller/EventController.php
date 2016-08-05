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
    public function indexAction()  {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TimeTMCoreBundle:Event')->findAll();

        return $this->render('TimeTMCoreBundle:Event:index.html.twig', array('entities' => $entities));
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

        $form = $this->createCreateForm($event);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $rawduration = $event->getStartdate()->diff($event->getEnddate());
            $duration = $rawduration->h . '.' . $rawduration->i / 0.6;

            $event->setDuration($duration);

            $em->persist($event);
            $em->flush();

            if ($request->isXmlHttpRequest()) {

            	$response['success'] = true;
            	$response['referer'] = $request->getSession()->get('ttm/event/referer');

            	return new JsonResponse( $response );
            }

            return $this->redirect($request->getSession()->get('ttm/event/referer'));

//             if ( $year == date('Y') || $month == date('m') ) {
//             	return $this->redirect($this->generateUrl('month_no_param'));
//             }

//             return $this->redirect($this->generateUrl('month', array('year' => $year, 'month' => $month )));
        }
        else {
        	if ( $request->isXmlHttpRequest()) {

			    // -- create parameters array
			    $params = array (
			    	// event parameters
			    	'entity' => $event,
			    	'form'   => $form->createView(),
			    	// template to include
			    	'template' => 'new',
			    	'buttonText' => 'close'
			    );

			    return $this->render( 'TimeTMCoreBundle:Event:ajax.html.twig', $params );
		    }
        }

        // get a new calendar
        $calendar = $this->get('timetm.calendar.month');

        // initialize the calendar
        // TODO : try to get year and month from event
        $calendar->init( array (
        	'year' => date('Y'),
        	'month' => date('m'),
        ));

        // get common template params
        $params = $this->get('timetm.calendar.helper')->getBaseTemplateParams($calendar);

        // -- add template params
        // monthPanel parameters
        $params['days'] = $calendar->getMonthCalendarDates();
        $params['entity'] = $event;
        $params['form'] = $form->createView();
        $params['template'] = 'new';
        $params['buttonText'] = 'action.back.list';
        return $this->render('TimeTMCoreBundle:Event:event.html.twig', $params);
    }

    /**
    * Creates a form to create a Event entity.
    *
    * @param Event $event
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Event $event)
    {
    	$userId = $this->getUser()->getId();

        $form = $this->createForm(EventType::class, $event, array(
            'action' => $this->generateUrl('event_create'),
            'method' => 'POST',
            'entity_manager' => $this->get('doctrine.orm.entity_manager'),
            'user' => $this->getUser()->getId()
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
        $form = $this->createCreateForm($event);

        // -- add template params
		$params = array();
        $params['entity']   = $event;
        $params['form']     = $form->createView();
        $params['template'] = 'new';

        // ajax detection
        if ($request->isXmlHttpRequest()) {
        	$params['buttonText'] = 'action.close';
        	return $this->render( 'TimeTMCoreBundle:Event:ajax.html.twig', $params );
        }

        // get a new calendar
        $calendar = $this->get('timetm.calendar.month');

        // initialize the calendar
        $calendar->init(array('year' => $year, 'month' => $month));

        // add common template params
        $params = \array_merge($params,$this->get('timetm.calendar.helper')->getBaseTemplateParams($calendar));

        // no ajax
        $params['buttonText'] = 'action.back.list';
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
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('TimeTMCoreBundle:Event')->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        // -- add template params
        $params = array();
        $params['entity']     = $event;
        $params['delete_form'] = $deleteForm->createView();
        $params['template']    = 'show';

        // ajax detection



        // get a new calendar
        $calendar = $this->get('timetm.calendar.month');

        // initialize the calendar
        $calendar->init(array('year' => $event->getStartdate()->format('Y'), 'month' => $event->getStartdate()->format('m')));

        // add common template params
        $params = \array_merge($params,$this->get('timetm.calendar.helper')->getBaseTemplateParams($calendar));


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
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('TimeTMCoreBundle:Event')->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $editForm = $this->createEditForm($event);
        $deleteForm = $this->createDeleteForm($id);

        // -- add template params
        $params = array();
        $params['entity']      = $event;
        $params['edit_form']   = $editForm->createView();
        $params['delete_form'] = $deleteForm->createView();
        $params['template']    = 'edit';

        // ajax detection



        // get a new calendar
        $calendar = $this->get('timetm.calendar.month');

        // initialize the calendar
        $calendar->init(array('year' => $event->getStartdate()->format('Y'), 'month' => $event->getStartdate()->format('m')));

        // add common template params
        $params = \array_merge($params,$this->get('timetm.calendar.helper')->getBaseTemplateParams($calendar));

        $params['buttonText'] = 'action.back.list';

        return $this->render('TimeTMCoreBundle:Event:event.html.twig', $params);
    }

    /**
    * Creates a form to edit a Event entity.
    *
    * @param Event $event The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Event $event)
    {
        $form = $this->createForm(EventType::class, $event, array(
            'action' => $this->generateUrl('event_update', array('id' => $event->getId())),
            'method' => 'PUT',
            'entity_manager' => $this->get('doctrine.orm.entity_manager'),
            'user' => $this->getUser()->getId()
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
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('TimeTMCoreBundle:Event')->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($event);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('event_edit', array('id' => $id)));
        }

        return $this->render('TimeTMCoreBundle:Event:edit.html.twig', array(
            'entity'      => $event,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ));
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
    public function deleteAction(Request $request, $id)
    {
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
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('event_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
