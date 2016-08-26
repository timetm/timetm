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
use Symfony\Component\Form\FormError;
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
class AgendaController extends Controller
{

    /**
     * Lists all Agenda entities.
     *
     * @return array polo
     *
     * @Route("/", name="agenda")
     * @Method("GET")
     */
    public function indexAction() {

        $entities = $this->getUser()->getAgendas();

        return $this->render('TimeTMCoreBundle:Agenda:index.html.twig', array('entities' => $entities));
    }

    /**
     * Creates a new Agenda entity.
     *
     * @Route("/", name="agenda_create")
     * @Method("POST")
     */
    public function createAction(Request $request) {

        $entity = new Agenda();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $entity->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('agenda_show', array('id' => $entity->getId())));
        }

        return $this->render('TimeTMCoreBundle:Agenda:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Agenda entity.
    *
    * @param Agenda $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Agenda $entity) {

        $form = $this->createForm(AgendaType::class, $entity, array(
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
    public function newAction() {

        $entity = new Agenda();
        $form   = $this->createCreateForm($entity);

        return $this->render('TimeTMCoreBundle:Agenda:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Finds and displays a Agenda entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="agenda_show")
     * @Method("GET")
     */
    public function showAction($id) {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TimeTMCoreBundle:Agenda')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Agenda entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TimeTMCoreBundle:Agenda:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Agenda entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="agenda_edit")
     * @Method("GET")
     */
    public function editAction($id) {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TimeTMCoreBundle:Agenda')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Agenda entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TimeTMCoreBundle:Agenda:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
    * Creates a form to edit a Agenda entity.
    *
    * @param Agenda $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Agenda $entity) {

        $form = $this->createForm(AgendaType::class, $entity, array(
            'action' => $this->generateUrl('agenda_update', array('id' => $entity->getId())),
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

        $entity = $em->getRepository('TimeTMCoreBundle:Agenda')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Agenda entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);


        /**
         * if agenda is not set to default, check if there's a default Agenda
         *
         * if not add form error
         */
        $editForm = $this->get('timetm.agenda.helper')->defaultAttributeCheck($editForm);


        if ($editForm->isValid()) {

            /**
             * if agenda set to default,remove previous default
             */
            $this->get('timetm.agenda.helper')->setDefaultAttribute($entity);

            $entity->setUser($this->getUser());
            $em->flush();
            return $this->redirect($this->generateUrl('agenda_show', array('id' => $id)));
        }

        return $this->render('TimeTMCoreBundle:Agenda:edit.html.twig', array(
        	'entity'      => $entity,
        	'edit_form'   => $editForm->createView(),
        	'delete_form' => $deleteForm->createView()
        ));
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
            $entity = $em->getRepository('TimeTMCoreBundle:Agenda')->find($id);

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
    private function createDeleteForm($id) {

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('agenda_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
