<?php

namespace App\Controller;

use App\Application\Service\AddNewTask;
use App\Application\Service\AddNewTaskUseCase;
use App\Entity\Task;
use App\Form\AddTaskForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    private AddNewTaskUseCase $service;

    public function __construct(AddNewTaskUseCase $service)
    {
        $this->service = $service;
    }

    #[Route('/add', name: 'app_task')]
    public function add(Request $request): Response
    {
        $task = new Task();

        $form = $this->createForm(AddTaskForm::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->execute(
                new AddNewTask($task)
            );

            return $this->redirectToRoute('app_index');
        }

        return $this->render('task/add.html.twig', [
            'addTaskForm' => $form,
        ]);
    }
}
