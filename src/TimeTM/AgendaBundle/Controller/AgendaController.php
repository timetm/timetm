<?php
/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\AgendaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TimeTM\AgendaBundle\Entity\Agenda;
use TimeTM\AgendaBundle\Form\Type\AgendaType;

/**
 * Agenda controller.
 *
 * @Route("/agenda")
 */
class AgendaController extends Controller
{

    /**
     * Lists all Agenda entities.
     * 
     * @return array polo
     *
     * @Route("/", name="agenda")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TimeTMAgendaBundle:Agenda')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Agenda entity.
     *
     * @Route("/", name="agenda_create")
     * @Method("POST")
     * @Template("TimeTMAgendaBundle:Agenda:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Agenda();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('agenda_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Agenda entity.
    *
    * @param Agenda $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Agenda $entity)
    {
        $form = $this->createForm(new AgendaType(), $entity, array(
            'action' => $this->generateUrl('agenda_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Agenda entity.
     *
     * @Route("/new", name="agenda_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Agenda();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Agenda entity.
     * 
     * @param integer $id
     *
     * @Route("/{id}", name="agenda_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TimeTMAgendaBundle:Agenda')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Agenda entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Agenda entity.
     * 
     * @param integer $id
     *
     * @Route("/{id}/edit", name="agenda_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TimeTMAgendaBundle:Agenda')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Agenda entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Agenda entity.
    *
    * @param Agenda $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Agenda $entity)
    {
        $form = $this->createForm(new AgendaType(), $entity, array(
            'action' => $this->generateUrl('agenda_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Agenda entity.
     * 
     * @param integer $id
     *
     * @Route("/{id}", name="agenda_update")
     * @Method("PUT")
     * @Template("TimeTMAgendaBundle:Agenda:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TimeTMAgendaBundle:Agenda')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Agenda entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('agenda_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Agenda entity.
     * 
     * @param integer $id
     *
     * @Route("/{id}", name="agenda_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TimeTMAgendaBundle:Agenda')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Agenda entity.');
            }

            $em->remove($entity);
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
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('agenda_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
