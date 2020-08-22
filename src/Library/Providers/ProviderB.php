<?php


namespace App\Library\Providers;


use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class ProviderB extends TodoProvider implements TodoProviderInterface
{

    const API_URL = 'http://www.mocky.io/v2/5d47f235330000623fa3ebf7';
    const HTTP_METHOD = 'GET';

    /**
     * @var EntityManagerInterface $entityManager
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->setApiUrl(self::API_URL);
        $this->setHttpMethod(self::HTTP_METHOD);
        $this->manager = $manager;
    }

    public function handle(): void
    {
        try {
            $todoData = $this->getTodoData();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        foreach ($todoData as $todoDatum) {
            foreach ($todoDatum as $name => $datum) {
                $task = new Task();
                $task->setName($name);
                $task->setLevel($datum['level']);
                $task->setEstimatedDuration($datum['estimated_duration']);
                $task->setCost($task->getEstimatedDuration() * $task->getLevel());
                $this->manager->persist($task);
            }
        }

        $this->manager->flush();
    }
}