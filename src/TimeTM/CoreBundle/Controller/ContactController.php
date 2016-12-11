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
use TimeTM\CoreBundle\Entity\Contact;
use TimeTM\CoreBundle\Form\Type\ContactType;

/**
 * Contact controller.
 *
 * @Route("/contact")
 *
 * @author André Friedli <a@frian.org>
 */
class ContactController extends Controller {
    
    /**
     * Lists all Contact entities.
     *
     * @Route("/", name="contact")
     * @Method("GET")
     */
    public function indexAction(Request $request) {

        // store the route in session (referer for event add)
		$request->getSession()->set('ttm/event/referer', $request->getRequestUri());

        $em = $this->getDoctrine()->getManager();

        $contacts = $em->getRepository('TimeTMCoreBundle:Contact')->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $contacts, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        $params = array(
            'entities' => $pagination,
            'template' => 'index',
            'buttonText' => 'action.back.list'
        );

        // ajax detection
        if ($request->isXmlHttpRequest()) {
        	$params['buttonText'] = 'action.close';
        	return $this->render( 'TimeTMCoreBundle:Contact:index.html.twig', $params );
        }

        // add common template params
        $params = \array_merge($params, $this->get('timetm.calendar.helper')->getCalendarTemplateParams());

        return $this->render('TimeTMCoreBundle:Contact:contact.html.twig', $params);
    }

    /**
     * Creates a new Contact entity.
     *
     * @Route("/", name="contact_create")
     * @Method("POST")
     */
    public function createAction(Request $request) {

        $contact = new Contact();

        $form = $this->createCreateForm($contact);
        $form->handleRequest($request);

        $params = array(
            'entity' => $contact,
            'form'   => $form->createView(),
            'buttonText' => 'action.back.list',
            'template' => 'new'
        );

        if ($form->isValid()) {

        	$helper = $this->get('timetm.contact.helper');

        	// check if firstname is defined
			$contact = $helper->parseNameField($contact);

        	$msg = $helper->setCanonicalName($contact);

            if ( $msg ) {

                $params['msg'] = $msg;

                if ( $request->isXmlHttpRequest()) {
                    $params['buttonText'] = 'action.close';
                    return $this->render( 'TimeTMCoreBundle:Contact:ajax.html.twig', $params );
                }

                // add common template params
                $params = \array_merge($params, $this->get('timetm.calendar.helper')->getCalendarTemplateParams());

                return $this->render('TimeTMCoreBundle:Contact:contact.html.twig', $params);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            if ($request->isXmlHttpRequest()) {

            	$response['success'] = true;
            	$response['referer'] = $request->getSession()->get('ttm/event/referer');

            	return new JsonResponse( $response );
            }

            return $this->redirect($this->generateUrl('contact_show', array('id' => $contact->getId())));
        }
        else {
            if ( $request->isXmlHttpRequest()) {

                $params['buttonText'] = 'action.close';

                return $this->render( 'TimeTMCoreBundle:Contact:ajax.html.twig', $params );
            }
        }

        // add common template params
        $params = \array_merge($params, $this->get('timetm.calendar.helper')->getCalendarTemplateParams());

        return $this->render('TimeTMCoreBundle:Contact:contact.html.twig', $params);
    }

    /**
     * Creates a form to create a Contact entity.
     *
     * @param Contact $contact The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Contact $contact) {

        $form = $this->createForm(ContactType::class, $contact, array(
            'action' => $this->generateUrl('contact_create'),
            'method' => 'POST',
        ));

        $form->add('save', SubmitType::class, array('label' => 'action.save'));

        return $form;
    }

    /**
     * Displays a form to create a new Contact entity.
     *
     * @Route("/new", name="contact_new")
     * @Method("GET")
     */
    public function newAction(Request $request) {

        $contact = new Contact();
        $form   = $this->createCreateForm($contact);

        $params = array(
            'entity'   => $contact,
            'form'     => $form->createView(),
            'template' => 'new',
            'buttonText' => 'action.back.list'
        );

        // ajax detection
        if ($request->isXmlHttpRequest()) {
        	$params['buttonText'] = 'action.close';
        	return $this->render( 'TimeTMCoreBundle:Contact:ajax.html.twig', $params );
        }

        // add common template params
        $params = \array_merge($params, $this->get('timetm.calendar.helper')->getCalendarTemplateParams());

        // no ajax
        return $this->render('TimeTMCoreBundle:Contact:contact.html.twig', $params);
    }

    /**
     * Finds and displays a Contact entity.
     *
     * @param int $id
     *
     * @Route("/{id}", name="contact_show")
     * @Method("GET")
     */
    public function showAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository('TimeTMCoreBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $params = array(
            'entity'      => $contact,
            'delete_form' => $deleteForm->createView(),
            'template'    => 'show',
            'buttonText' => 'action.back.list'
        );

        // ajax detection
        if ($request->isXmlHttpRequest()) {
            $params['buttonText'] = 'close';
        	return $this->render( 'TimeTMCoreBundle:Contact:ajax.html.twig', $params );
        }

        // add common template params
        $params = \array_merge($params, $this->get('timetm.calendar.helper')->getCalendarTemplateParams());

        return $this->render('TimeTMCoreBundle:Contact:contact.html.twig', $params);
    }

    /**
     * Displays a form to edit an existing Contact entity.
     *
     * @param int $id
     *
     * @Route("/{id}/edit", name="contact_edit")
     * @Method("GET")
     */
    public function editAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository('TimeTMCoreBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $editForm = $this->createEditForm($contact);
        $deleteForm = $this->createDeleteForm($id);

        $params = array(
            'entity'      => $contact,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'template'    => 'edit',
            'buttonText' => 'action.back.list'
        );

        // ajax detection
        if ($request->isXmlHttpRequest()) {
            $params['buttonText'] = 'close';
        	return $this->render( 'TimeTMCoreBundle:Contact:ajax.html.twig', $params );
        }

        // add common template params
        $params = \array_merge($params, $this->get('timetm.calendar.helper')->getCalendarTemplateParams());

        return $this->render('TimeTMCoreBundle:Contact:contact.html.twig', $params);
    }

    /**
    * Creates a form to edit a Contact entity.
    *
    * @param Contact $contact The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Contact $contact) {

        $form = $this->createForm(ContactType::class, $contact, array(
            'action' => $this->generateUrl('contact_update', array('id' => $contact->getId())),
            'method' => 'PUT',
        ));

        $form->add('save', SubmitType::class, array('label' => 'action.update'));

        return $form;
    }

    /**
     * Edits an existing Contact entity.
     *
     * @param int $id
     *
     * @Route("/{id}", name="contact_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository('TimeTMCoreBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($contact);
        $editForm->handleRequest($request);

        $params = array(
            'entity'      => $contact,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'template'   => 'edit',
            'buttonText' => 'action.back.list'
        );

        if ($editForm->isValid()) {

            // TODO check if canonical_name elements have change, if yes uncomment below

        	// $helper = $this->get('timetm.contact.helper');

            // $msg = $helper->setCanonicalName($contact);
            //
            // if ( $msg ) {
            //     return $this->render('TimeTMCoreBundle:Contact:edit.html.twig', array(
            //         'entity'      => $contact,
            //         'edit_form'   => $editForm->createView(),
            //         'delete_form' => $deleteForm->createView(),
            //     	'msg'         => $msg,
            //         'buttonText'  => 'close'
            //     ));
            // }

            $em->flush();

            if ($request->isXmlHttpRequest()) {

            	$response['success'] = true;
            	$response['referer'] = $request->getSession()->get('ttm/event/referer');

            	return new JsonResponse( $response );
            }

            return $this->redirect($this->generateUrl('contact_show', array('id' => $id)));
        }
        else {
            if ( $request->isXmlHttpRequest()) {

                $params['buttonText'] = 'action.close';

                return $this->render( 'TimeTMCoreBundle:Contact:ajax.html.twig', $params );
            }
        }

        // add common template params
        $params = \array_merge($params, $this->get('timetm.calendar.helper')->getCalendarTemplateParams());

        return $this->render('TimeTMCoreBundle:Contact:contact.html.twig', $params);
    }

    /**
     * Deletes a Contact entity.
     *
     * @param int $id
     *
     * @Route("/{id}", name="contact_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {

        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $contact = $em->getRepository('TimeTMCoreBundle:Contact')->find($id);

            if (!$contact) {
                throw $this->createNotFoundException('Unable to find Contact entity.');
            }

            $em->remove($contact);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('contact'));
    }

    /**
     * Creates a form to delete a Contact entity by id.
     *
     * @param int $id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contact_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
