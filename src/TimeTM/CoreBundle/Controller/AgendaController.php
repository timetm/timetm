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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use TimeTM\CoreBundle\Entity\Agenda;
use TimeTM\CoreBundle\Form\Type\AgendaType;

/**
 * Agenda controller.
 *
 * @Route("/agenda")
 *
 * @author André Friedli <a@frian.org>
 */
class AgendaController extends Controller {

    /**
     * Lists all Agendas of the current user.
     *
     * @Route("/", name="agenda")
     * @Method("GET")
     */
    public function indexAction(Request $request) {

        // store the route in session (referer for agenda add)
		$request->getSession()->set('ttm/event/referer', $request->getRequestUri());

        $agendas = $this->getUser()->getAgendas();

        $params = array(
            'agendas' => $agendas,
            'template' => 'index',
        );

        // ajax detection
        if ($request->isXmlHttpRequest()) {
        	return $this->render( 'TimeTMCoreBundle:Agenda:index.html.twig', $params );
        }

        return $this->render('TimeTMCoreBundle:Agenda:agenda.html.twig', $params);
    }

    /**
     * Creates a new Agenda entity.
     *
     * @Route("/", name="agenda_create")
     * @Method("POST")
     */
    public function createAction(Request $request) {

        $agenda = new Agenda();
        $form = $this->createCreateForm($agenda);
        $form->handleRequest($request);

        $params = array(
            'agenda' => $agenda,
            'form'   => $form->createView(),
            'buttonText' => 'action.back.list',
            'template' => 'new',
        );

        if ($form->isValid()) {

            $agenda->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($agenda);
            $em->flush();

            if ($request->isXmlHttpRequest()) {

            	$response['success'] = true;
            	$response['referer'] = $request->getSession()->get('ttm/event/referer');

            	return new JsonResponse( $response );
            }

            return $this->redirect($this->generateUrl('agenda_show', array('id' => $agenda->getId())));
        }
        else {
            if ( $request->isXmlHttpRequest()) {

                $params['buttonText'] = 'action.close';

                return $this->render( 'TimeTMCoreBundle:Agenda:ajax.html.twig', $params );
            }
        }

        return $this->render('TimeTMCoreBundle:Agenda:agenda.html.twig', $params);
    }

    /**
    * Creates a form to create a Agenda entity.
    *
    * @param Agenda $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Agenda $agenda) {

        $form = $this->createForm(AgendaType::class, $agenda, array(
            'action' => $this->generateUrl('agenda_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'action.save'));

        return $form;
    }

    /**
     * Displays a form to create a new Agenda entity.
     *
     * @Route("/new", name="agenda_new")
     * @Method("GET")
     */
    public function newAction(Request $request) {

        $agenda = new Agenda();
        $form   = $this->createCreateForm($agenda);

        $params = array(
            'agenda' => $agenda,
            'form'   => $form->createView(),
            'template' => 'new',
            'buttonText' => 'action.back.list'
        );

        // ajax detection
        if ($request->isXmlHttpRequest()) {
            $params['buttonText'] = 'action.close';
        	return $this->render( 'TimeTMCoreBundle:Agenda:ajax.html.twig', $params );
        }

        return $this->render('TimeTMCoreBundle:Agenda:agenda.html.twig', $params);
    }

    /**
     * Finds and displays a Agenda entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="agenda_show")
     * @Method("GET")
     */
    public function showAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();

        $agenda = $em->getRepository('TimeTMCoreBundle:Agenda')->find($id);

        if (!$agenda) {
            throw $this->createNotFoundException('Unable to find Agenda entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $params = array(
            'agenda'      => $agenda,
            'delete_form' => $deleteForm->createView(),
            'template'    => 'show',
            'buttonText'  => 'action.back.list'
        );

        // ajax detection
        if ($request->isXmlHttpRequest()) {
            $params['buttonText'] = 'action.close';
        	return $this->render( 'TimeTMCoreBundle:Agenda:ajax.html.twig', $params );
        }

        return $this->render('TimeTMCoreBundle:Agenda:agenda.html.twig', $params);
    }

    /**
     * Displays a form to edit an existing Agenda entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="agenda_edit")
     * @Method("GET")
     */
    public function editAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();

        $agenda = $em->getRepository('TimeTMCoreBundle:Agenda')->find($id);

        if (!$agenda) {
            throw $this->createNotFoundException('Unable to find Agenda entity.');
        }

        $editForm = $this->createEditForm($agenda);
        $deleteForm = $this->createDeleteForm($id);

        $params = array(
            'agenda'      => $agenda,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'template'    => 'edit',
            'buttonText'  => 'action.back.list'
        );

        // ajax detection
        if ($request->isXmlHttpRequest()) {
            $params['buttonText'] = 'action.close';
        	return $this->render( 'TimeTMCoreBundle:Agenda:ajax.html.twig', $params );
        }

        return $this->render('TimeTMCoreBundle:Agenda:agenda.html.twig', $params);
    }

    /**
    * Creates a form to edit a Agenda entity.
    *
    * @param Agenda $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Agenda $agenda) {

        $form = $this->createForm(AgendaType::class, $agenda, array(
            'action' => $this->generateUrl('agenda_update', array('id' => $agenda->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'action.update'));

        return $form;
    }

    /**
     * Edits an existing Agenda entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="agenda_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();

        $agenda = $em->getRepository('TimeTMCoreBundle:Agenda')->find($id);

        if (!$agenda) {
            throw $this->createNotFoundException('Unable to find Agenda entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($agenda);
        $editForm->handleRequest($request);


        /**
         * if agenda is not set to default, check if there's a default Agenda
         *
         * if not add form error
         */
        $editForm = $this->get('timetm.agenda.helper')->defaultAttributeCheck($editForm);

        $params = array(
        	'agenda'      => $agenda,
        	'edit_form'   => $editForm->createView(),
        	'delete_form' => $deleteForm->createView(),
            'template'    => 'edit',
            'buttonText' => 'action.back.list'
        );

        if ($editForm->isValid()) {

            /**
             * if agenda set to default,remove previous default
             */
            $this->get('timetm.agenda.helper')->setDefaultAttribute($agenda);

            $agenda->setUser($this->getUser());
            $em->flush();

            if ($request->isXmlHttpRequest()) {

                $response['success'] = true;
                $response['referer'] = $request->getSession()->get('ttm/event/referer');

                return new JsonResponse( $response );
            }

            return $this->redirect($this->generateUrl('agenda_show', array('id' => $id)));
        }
        else {
            if ( $request->isXmlHttpRequest()) {

                $params['buttonText'] = 'action.close';

                return $this->render( 'TimeTMCoreBundle:Agenda:ajax.html.twig', $params );
            }
        }

        return $this->render('TimeTMCoreBundle:Agenda:agenda.html.twig', $params);
    }

    /**
     * Deletes a Agenda entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="agenda_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {

        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $agenda = $em->getRepository('TimeTMCoreBundle:Agenda')->find($id);

            if (!$agenda) {
                throw $this->createNotFoundException('Unable to find Agenda entity.');
            }

            $em->remove($agenda);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('agenda'));
    }

    /**
     * Creates a form to delete a Agenda entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('agenda_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
