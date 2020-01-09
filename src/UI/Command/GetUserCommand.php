<?php

namespace Directory\UI\Command;

use Directory\Application\Exception\ApplicationException;
use Directory\Application\Query\QueryBus;
use Directory\Application\Query\User\DTO\UserDTO;
use Directory\Application\Query\User\GetUserById;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GetUserCommand extends Command
{
    public static $defaultName = 'directory:user:get';

    /** @var QueryBus */
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        parent::__construct();
        $this->queryBus = $queryBus;
    }

    public function configure(): void
    {
        $this->addArgument('userId', InputArgument::REQUIRED, 'The user id to find');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        /** @var string $userId */
        $userId = $input->getArgument('userId');

        try {
            /** @var UserDTO $user */
            $user = $this->queryBus->query(new GetUserById($userId));
        } catch (ApplicationException $exception) {
            $io->error($exception->getMessage());

            return 1;
        }

        $io->success('User found!');

        $headers = ['Id', 'Name'];
        $data = [
            $user->getId(),
            $user->getName(),
        ];

        foreach ($user->getProperties() as $name => $value) {
            $headers[] = $name;
            $data[] = $value;
        }

        $io->horizontalTable($headers, [$data]);

        return 0;
    }
}
