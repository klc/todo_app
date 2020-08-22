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

class FetchTasksCommand extends Command
{
    protected static $defaultName = 'to-do:fetch';

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Fetch tasks from to-do providers');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('Start fetching tasks');

        $fetchTask = new FetchTasks();
        $fetchTask->addTodoProvider(new ProviderA($this->manager))
            ->addTodoProvider(new ProviderB($this->manager))
            ->handle();

        $io->success('Finished fetching');

        return 0;
    }
}
