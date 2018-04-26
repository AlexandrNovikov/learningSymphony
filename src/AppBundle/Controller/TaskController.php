<?php

namespace AppBundle\Controller;

use AppBundle\Form\TaskType;
use AppBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class TaskController extends Controller
{
    /**
     * @Route("/tasks", name="tasks_list")
     */
    public function listAction()
    {
        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findAll();

        return $this->render('tasks/tasksList.html.twig', ['myArr' => $tasks]);

    }

    /**
     * @Route("/task")
     */
    public function newAction(Request $request)
    {
        // just setup a fresh $task object (remove the dummy data)
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($task);
             $entityManager->flush();

            return $this->redirectToRoute('tasks_list');
        }

        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/task/{taskId}", name="task_show")
     */
    public function showAction($taskId)
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($taskId);

        if (!$task) {
            throw $this->createNotFoundException(
                'No task found for id '.$taskId
            );
        }
        $result = $task->getDueDate()->format('Y-m-d H:i:s');


        return $this->render('tasks/showTask.html.twig', ['taskText' => $task->getTask(), 'taskDate' => $result,
            'taskId' =>  $task->getId()]);
    }

    /**
     * @Route("/task/delete/{taskId}", name="task_delete")
     */
    public function deleteAction($taskId)
    {
        $entityManager = $this->getDoctrine()->getManager(); //FIXME Refactor it. Move declaration of EntityManager object from methods for reusing. DRY

        $task = $this->getDoctrine() //FIXME Refactor it. Move declaration of Task object from methods for reusing. DRY
            ->getRepository(Task::class)
            ->find($taskId);

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('tasks_list');

    }

    /**
     * @Route("task/edit/{taskId}", name="task_edit")
     */
    public function editAction($taskId, Request $request)
    {
        $task = $this->getDoctrine() //FIXME Refactor it. Move declaration of Task object from methods for reusing. DRY
        ->getRepository(Task::class)
            ->find($taskId);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('task_show', ['taskId'=>$taskId]);
        }

        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}