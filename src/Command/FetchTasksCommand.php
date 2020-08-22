<?php

namespace App\Command;

use App\Library\FetchTasks;
use App\Library\Providers;
use App\Library\Providers\ProviderA;
use App\Library\Providers\ProviderB;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FetchTasksCommand extends Command
{
    protected static $defaultName = 'to-do:fetch';

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var HttpClientInterface $httpClient
     */
    private $httpClient;

    public function __construct(EntityManagerInterface $manager, HttpClientInterface $httpClient)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->httpClient = $httpClient;
    }

    protected function configure()
    {
        $this->setDescription('Fetch tasks from to-do providers');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('Start fetching tasks');

        $fetchTask = new FetchTasks($this->manager);
        $fetchTask->addTodoProvider(new ProviderA($this->httpClient))
            ->addTodoProvider(new ProviderB($this->httpClient))
            ->handle();

        $io->success('Finished fetching');

        return 0;
    }
}
