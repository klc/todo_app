<?php


namespace App\Library;


use App\Library\Providers\TodoProviderInterface;

class FetchTasks
{
    private $providers = [];

    public function addTodoProvider(TodoProviderInterface $todoProvider): FetchTasks
    {
        $this->providers[] = $todoProvider;

        return $this;
    }

    public function handle(): void
    {
        foreach ($this->providers as $provider) {
            $provider->handle();
        }
    }

}