<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    /**
     * @Route("/task/", name="add_task")
     * @Route("/task/{id}", name="edit_task")
     * @Template()
     */
    public function addAction(Request $request, $id)
    {
        $task = $this->getDoctrine()->getManager()->find('AppBundle:Task', $id);
        if (is_null($task)) {
            die('theres no task man');
            $task = Task::make();
        }
        $form = $this->createForm(TaskType::class, $task);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $objectManager = $this->getDoctrine()->getManager();
            $objectManager->persist($task);
            $objectManager->flush();

            return $this->redirectToRoute('tasks');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/tasks", name="tasks")
     * @Template()
     */
    public function listAction(Request $request)
    {
        // replace this example code with whatever you need
        return [
            'tasks' => $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('AppBundle:Task')
                ->findAll()
            ,
        ];
    }

}