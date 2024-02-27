<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

    #[Route('/task/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task, [
            'action' => $this->generateUrl('app_task_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            echo "<pre>";
            print_r($task);
            exit();

            // ... perform some action, such as saving the task to the database

            // return $this->redirectToRoute('task_success');
        }

        return $this->render('task/new.html.twig', [
            'form' => $form,
            'title' => 'Create Task'
        ]);
    }
}
