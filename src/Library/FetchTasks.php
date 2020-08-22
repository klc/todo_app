<?php


namespace App\Library;


use App\Entity\Task;
use App\Library\Providers\TodoProviderInterface;
use Doctrine\ORM\EntityManagerInterface;

class FetchTasks
{
    /**
     * @var array $providers
     */
    private $providers = [];

    /**
     * @var EntityManagerInterface $manager
     */
    private $manager;

    /**
     * FetchTasks constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param TodoProviderInterface $todoProvider
     * @return self
     */
    public function addTodoProvider(TodoProviderInterface $todoProvider): self
    {
        $this->providers[] = $todoProvider;

        return $this;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        foreach ($this->providers as $provider) {
            $provider->handle();

            foreach ($provider->getMappedData() as $datum) {
                $task = new Task();
                $task->setName($datum['name']);
                $task->setLevel($datum['level']);
                $task->setEstimatedDuration($datum['estimated_duration']);
                $task->setCost($task->getEstimatedDuration() * $task->getLevel());
                $this->manager->persist($task);
            }
        }

        $this->manager->flush();
    }
}