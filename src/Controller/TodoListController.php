<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TodoListController extends AbstractController
{
    /**
     * @Route("/", name="todo_list")
     */
    public function index()
    {
        $developers = $this->getDoctrine()
            ->getRepository(Developer::class)
            ->findBy(
                [],
                ['efficiency' => 'DESC']
            );

        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findBy(
                [],
                ['cost' => 'DESC']
            );

        foreach ($tasks as $task) {
            $developerCosts = [];
            foreach ($developers as $developer) {
                $cost = ($task->getCost() / $developer->getEfficiency()) + $developer->getTotalTaskDuration();
                $developerCosts[$cost] = $developer;
            }

            krsort($developerCosts);

            $developer = array_pop($developerCosts);
            $developer->addTaskDuration($task->getCost());
            $developer->addTask($task, $task->getCost());
        }

        $weekCount = 0;

        foreach ($developers as $developer) {
            $developerWeekCount = count($developer->getTasks());
            if ($developerWeekCount > $weekCount) {
                $weekCount = $developerWeekCount;
            }
        }

        return $this->render('todo_list/index.html.twig', compact('developers', 'weekCount'));
    }
}
