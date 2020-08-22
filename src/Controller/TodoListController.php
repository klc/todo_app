<?php

namespace App\Controller;

use App\Library\TaskDistributor;
use App\Repository\DeveloperRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoListController extends AbstractController
{
    /**
     * @Route("/", name="todo_list")
     * @param DeveloperRepository $developerRepository
     * @param TaskRepository $taskRepository
     * @param TaskDistributor $taskDistributor
     * @return Response
     */
    public function index(DeveloperRepository $developerRepository, TaskRepository $taskRepository, TaskDistributor $taskDistributor)
    {
        $developers = $developerRepository->findDevelopers();
        $tasks = $taskRepository->findTasks();

        $taskDistributor->setDevelopers($developers)
            ->setTasks($tasks)
            ->distribute();

        $weekCount = $taskDistributor->getWeekCount();

        return $this->render('todo_list/index.html.twig', compact('developers', 'weekCount'));
    }
}
