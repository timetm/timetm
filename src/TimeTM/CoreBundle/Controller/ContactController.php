<?php

namespace TimeTM\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TimeTM\CoreBundle\Entity\Contact;
use TimeTM\CoreBundle\Form\Type\ContactType;

/**
 * Contact controller.
 *
 * @Route("/contact")
 */
class ContactController extends Controller
{

    /**
     * Lists all Contact entities.
     *
     * @Route("/", name="contact")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contacts = $em->getRepository('TimeTMCoreBundle:Contact')->findAll();

        return array(
            'entities' => $contacts,
        );
    }
    /**
     * Creates a new Contact entity.
     *
     * @Route("/", name="contact_create")
     * @Method("POST")
     * @Template("TimeTMContactBundle:Contact:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createCreateForm($contact);
        $form->handleRequest($request);

        if ($form->isValid()) {

        	$helper = $this->get('timetm.contact.helper');

        	// check if firstname is defined
			$contact = $helper->parseNameField($contact);

			
        	list( $canonicalName, $msg) = $helper->getCanonicalName($contact);
        	 
        	$contact->setCanonicalName($canonicalName);

        	// standard code
            $em = $this->getDoctrine()->getManager();
            try {
	            $em->persist($contact);
	            $em->flush();
	        }
            catch (\Exception $e) {
	            switch( get_class($e)) {
	            	case 'Doctrine\DBAL\Exception\UniqueConstraintViolationException' :
	            		return array(
            				'entity' => $contact,
            				'form'   => $form->createView(),
            				'msg' => $msg
	            		);
	            		break;
            		default:
            			throw $e;
            			break;
	            }
            }
            return $this->redirect($this->generateUrl('contact_show', array('id' => $contact->getId())));
        }

        return array(
            'entity' => $contact,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Contact entity.
     *
     * @param Contact $contact The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Contact $contact)
    {
        $form = $this->createForm(new ContactType(), $contact, array(
            'action' => $this->generateUrl('contact_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Contact entity.
     *
     * @Route("/new", name="contact_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $contact = new Contact();
        $form   = $this->createCreateForm($contact);

        return array(
            'entity' => $contact,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Contact entity.
     *
     * @Route("/{id}", name="contact_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository('TimeTMCoreBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $contact,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Contact entity.
     *
     * @Route("/{id}/edit", name="contact_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository('TimeTMCoreBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $editForm = $this->createEditForm($contact);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $contact,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Contact entity.
    *
    * @param Contact $contact The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Contact $contact)
    {
        $form = $this->createForm(new ContactType(), $contact, array(
            'action' => $this->generateUrl('contact_update', array('id' => $contact->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Contact entity.
     *
     * @Route("/{id}", name="contact_update")
     * @Method("PUT")
     * @Template("TimeTMContactBundle:Contact:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository('TimeTMCoreBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($contact);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('contact_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $contact,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Contact entity.
     *
     * @Route("/{id}", name="contact_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
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
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contact_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}