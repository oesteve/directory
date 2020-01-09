<?php

namespace Directory\UI\Command;

use Directory\Application\Exception\ApplicationException;
use Directory\Application\Query\QueryBus;
use Directory\Application\Query\User\DTO\UserDTO;
use Directory\Application\Query\User\SearchUsers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SearchUsersCommand extends Command
{
    public static $defaultName = 'directory:user:search';

    /** @var QueryBus */
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        parent::__construct();
        $this->queryBus = $queryBus;
    }

    public function configure(): void
    {
        $this->addArgument('query', InputArgument::REQUIRED, 'The query to search');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $query = strval($input->getArgument('query'));
        try {
            /** @var UserDTO[] $users */
            $users = $this->queryBus->query(new SearchUsers($query));
        } catch (ApplicationException $e) {
            $io->error($e->getMessage());

            return 1;
        }

        if (empty($users)) {
            $io->error("Users not found for query: $query");

            return 1;
        }

        $io->success(sprintf('%d users found', count($users)));

        $headers = ['Name', 'Properties'];
        $data = [];

        foreach ($users as $user) {
            $userName = $user->getName();
            $props = null;

            foreach ($user->getProperties() as $name => $value) {
                $props .= ($props ? "\n" : null);
                $props .= sprintf('%s: %s', $name, $value);
            }

            $data[] = [$userName, $props];
        }

        $io->table($headers, $data);

        return 0;
    }
}
