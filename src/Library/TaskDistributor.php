<?php


namespace App\Library;


class TaskDistributor
{
    /**
     * @var array $tasks
     */
    private $tasks;

    /**
     * @var array $developers
     */
    private $developers;

    /**
     * @param array $tasks
     * @return self
     */
    public function setTasks(array $tasks): self
    {
        $this->tasks = $tasks;

        return $this;
    }

    /**
     * @param array $developers
     * @return self
     */
    public function setDevelopers(array $developers): self
    {
        $this->developers = $developers;

        return $this;
    }

    /**
     * @return void
     */
    public function distribute(): void
    {
        foreach ($this->tasks as $task) {
            $developerCosts = [];
            foreach ($this->developers as $developer) {
                $cost = ($task->getCost() / $developer->getEfficiency()) + $developer->getTotalTaskDuration();
                $developerCosts[$cost] = $developer;
            }

            krsort($developerCosts);

            $developer = array_pop($developerCosts);
            $developer->addTaskDuration($task->getCost());
            $developer->addTask($task, $task->getCost());
        }
    }

    /**
     * @return int
     */
    public function getWeekCount(): int
    {
        $weekCount = 0;

        foreach ($this->developers as $developer) {
            $developerWeekCount = count($developer->getTasks());
            if ($developerWeekCount > $weekCount) {
                $weekCount = $developerWeekCount;
            }
        }

        return $weekCount;
    }
}