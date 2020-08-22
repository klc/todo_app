<?php

namespace App\Entity;

use App\Repository\DeveloperRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeveloperRepository::class)
 */
class Developer
{
    const WORK_TIME = 45;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $efficiency;

    private $tasks;

    private $totalTaskDuration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEfficiency(): ?int
    {
        return $this->efficiency;
    }

    public function setEfficiency(int $efficiency): self
    {
        $this->efficiency = $efficiency;

        return $this;
    }

    public function addTask(Task $task, int $taskCost): self
    {
        $taskDuration = $taskCost / $this->getEfficiency();

        if (!$this->tasks) {
            $newWeek = [
                'weekly_task_duration' => $taskDuration > self::WORK_TIME ? 45 : $taskDuration,
                'tasks' => [ $task ]
            ];
            $this->tasks[] = $newWeek;

            if ($taskDuration > self::WORK_TIME) {
                $remainingCost = ($taskDuration - self::WORK_TIME) * $this->getEfficiency();
                $this->addTask($task, $remainingCost);
            }
        } else {
            $lastWeek = array_pop($this->tasks);

            if ($lastWeek['weekly_task_duration'] == self::WORK_TIME) {
                $this->tasks[] = $lastWeek;

                $newWeek = [
                    'weekly_task_duration' => $taskDuration > self::WORK_TIME ? 45 : $taskDuration,
                    'tasks' => [ $task ]
                ];

                $this->tasks[] = $newWeek;

                if ($taskDuration > self::WORK_TIME) {
                    $remainingCost = ($taskDuration - self::WORK_TIME) * $this->getEfficiency();
                    $this->addTask($task, $remainingCost);
                }
            } else {
                $remainingWorkTime = self::WORK_TIME - $lastWeek['weekly_task_duration'];

                if ($taskDuration > $remainingWorkTime) {
                    $lastWeek['weekly_task_duration'] = self::WORK_TIME;
                    $lastWeek['tasks'][] = $task;
                    $this->tasks[] = $lastWeek;

                    $remainingCost = ($taskDuration - $remainingWorkTime) * $this->getEfficiency();
                    $this->addTask($task, $remainingCost);
                } else {
                    $lastWeek['weekly_task_duration'] += $taskDuration;
                    $lastWeek['tasks'][] = $task;
                    $this->tasks[] = $lastWeek;
                }
            }
        }

        return $this;
    }

    public function addTaskDuration(int $taskCost): void
    {
        $this->totalTaskDuration += $taskCost / $this->getEfficiency();
    }

    public function getTotalTaskDuration(): int
    {
        return (int)$this->totalTaskDuration;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }
}
