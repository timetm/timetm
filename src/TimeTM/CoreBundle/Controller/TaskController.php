<?php

namespace TimeTM\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use TimeTM\CoreBundle\Entity\Task;

/**
 * Task controller.
 *
 * @Route("/task")
 */
class TaskController extends Controller
{
    /**
     * Lists all Task entities.
     *
     * @Route("/", name="task_index")
     * @Method("GET")
     */
    public function indexAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $tasks = $em->getRepository('TimeTMCoreBundle:Task')->findAll();

        $params = array(
            'entities' => $tasks,
            'template' => 'index',
            'buttonText' => 'action.back.list',
            'tasks' => $tasks
        );

        // ajax detection
        if ($request->isXmlHttpRequest()) {
        	$params['buttonText'] = 'action.close';
        	return $this->render( 'TimeTMCoreBundle:Task:index.html.twig', $params );
        }

        // add common template params
        $params = \array_merge($params, $this->get('timetm.calendar.helper')->getCalendarTemplateParams());

        return $this->render('TimeTMCoreBundle:Task:task.html.twig', $params);
    }

    /**
     * Creates a new Task entity.
     *
     * @Route("/new", name="task_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        $task = new Task();

        $task->setDuedate(new \DateTime(date("now")));

        $form = $this->createForm('TimeTM\CoreBundle\Form\Type\TaskType', $task);
        $form->add('save', SubmitType::class, array('label' => 'action.save'));

        $form->handleRequest($request);

        $params = array(
            'task'   => $task,
            'form'     => $form->createView(),
            'template' => 'new',
            'buttonText' => 'action.back.list'
        );

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('task_show', array('id' => $task->getId()));
        }

        // add common template params
        $params = \array_merge($params, $this->get('timetm.calendar.helper')->getCalendarTemplateParams());

        return $this->render('TimeTMCoreBundle:Task:task.html.twig', $params);
    }

    /**
     * Finds and displays a Task entity.
     *
     * @Route("/{id}", name="task_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Task $task) {

        $deleteForm = $this->createDeleteForm($task);

        $params = array(
            'task'        => $task,
            'delete_form' => $deleteForm->createView(),
            'template'    => 'show',
            'buttonText' => 'action.back.list'
        );

        // ajax detection
        if ($request->isXmlHttpRequest()) {
            $params['buttonText'] = 'close';
        	return $this->render( 'TimeTMCoreBundle:Task:ajax.html.twig', $params );
        }

        // add common template params
        $params = \array_merge($params, $this->get('timetm.calendar.helper')->getCalendarTemplateParams());

        return $this->render('TimeTMCoreBundle:Task:task.html.twig', $params);
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     * @Route("/{id}/edit", name="task_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Task $task) {

        $deleteForm = $this->createDeleteForm($task);
        $editForm = $this->createForm('TimeTM\CoreBundle\Form\Type\TaskType', $task);
        $editForm->handleRequest($request);

        $params = array(
            'task'        => $task,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'template'    => 'edit'
        );

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('task_edit', array('id' => $task->getId()));
        }

        // add common template params
        $params = \array_merge($params, $this->get('timetm.calendar.helper')->getCalendarTemplateParams());

        return $this->render('TimeTMCoreBundle:Task:task.html.twig', $params);
    }

    /**
     * Deletes a Task entity.
     *
     * @Route("/{id}", name="task_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Task $task) {

        $form = $this->createDeleteForm($task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
        }

        return $this->redirectToRoute('task_index');
    }

    /**
     * Creates a form to delete a Task entity.
     *
     * @param Task $task The Task entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Task $task) {

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('task_delete', array('id' => $task->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
