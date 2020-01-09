<?php

namespace Directory\UI\Command;

use Directory\Application\Command\CommandBus;
use Directory\Application\Command\User\CreateUser;
use Directory\Application\Exception\ApplicationException;
use Directory\Application\Fixture\UserFixtures;
use Directory\Application\Query\QueryBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LoadFixturesCommand extends Command
{
    public static $defaultName = 'fixtures:set';

    /** @var CommandBus */
    private $commandBus;

    /** @var QueryBus */
    private $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function configure(): void
    {
        $this->addUsage('Set fixture data');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        foreach (UserFixtures::sampleData() as $data) {
            try {
                $this->commandBus->dispatch(new CreateUser(
                    Uuid::uuid4()->toString(),
                    $data[0],
                    $data[1]));
            } catch (ApplicationException $exception) {
                $io->note($exception->getMessage());
            }
        }

        $io->success('Fixtures loaded');

        return 0;
    }
}
