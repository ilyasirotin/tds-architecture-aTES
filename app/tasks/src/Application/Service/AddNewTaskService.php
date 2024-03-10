<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Enqueue\Client\Message;
use Enqueue\Client\ProducerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\SerializerInterface;

final class AddNewTaskService implements AddNewTaskUseCase
{
    private TaskRepository $tasks;
    private Security $security;
    private UserRepository $users;
    private ProducerInterface $producer;
    private SerializerInterface $serializer;

    public function __construct(
        TaskRepository $tasks,
        UserRepository $users,
        Security $security,
        ProducerInterface $producer,
        SerializerInterface $serializer
    )
    {
        $this->tasks = $tasks;
        $this->security = $security;
        $this->users = $users;
        $this->producer = $producer;
        $this->serializer = $serializer;
    }

    public function execute(AddNewTask $command): Task
    {
        $newTask = $command->task();
        $author = $this->security->getUser();

        $newTask->setAuthor($author);

        $potentialAssigneeList = $this->users->findAssignees([$author], [
            'ROLE_MANAGER',
            'ROLE_ADMIN',
            'ROLE_ACCOUNTANT'
        ]);

        $randomAssignee = $potentialAssigneeList[array_rand($potentialAssigneeList)];
        $newTask->setAssignee($randomAssignee);

        $this->tasks->add($newTask);

        $message = new Message(
            $this->serializer->serialize($newTask, 'json')
        );

        $this->producer->sendEvent('tasks_stream', $message);
        $this->producer->sendEvent('task_created', $message);

        return $newTask;
    }
}
