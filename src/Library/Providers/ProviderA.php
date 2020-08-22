<?php


namespace App\Library\Providers;


use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class ProviderA extends TodoProvider implements TodoProviderInterface
{
    const API_URL = 'http://www.mocky.io/v2/5d47f24c330000623fa3ebfa';
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
            $task = new Task();
            $task->setName($todoDatum['id']);
            $task->setLevel($todoDatum['zorluk']);
            $task->setEstimatedDuration($todoDatum['sure']);
            $task->setCost($task->getEstimatedDuration() * $task->getLevel());
            $this->manager->persist($task);
        }

        $this->manager->flush();
    }
}