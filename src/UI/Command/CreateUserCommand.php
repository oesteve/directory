<?php

namespace Directory\UI\Command;

use Directory\Application\Command\CommandBus;
use Directory\Application\Command\User\CreateUser;
use Directory\Application\Exception\ApplicationException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateUserCommand extends Command
{
    public static $defaultName = 'directory:user:create';

    /** @var CommandBus */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    public function configure(): void
    {
        $this->addArgument('userName', InputArgument::REQUIRED, 'The user name');
        $this->addOption('property', 'p',InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'The user property as key=value', []);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = Uuid::uuid4()->toString();
        $userName = strval($input->getArgument('userName'));

        $io = new SymfonyStyle($input, $output);

        /** @var string[] $properties */
        $properties = $input->getOption('property');
        $propertyOptions = $this->parseProperties($properties);

        try {
            $this->commandBus->dispatch(new CreateUser($userId, $userName, $propertyOptions));
        } catch (ApplicationException $e) {
            $io->writeln($e->getMessage());

            return 1;
        }

        $output->writeln("User ${userName} was created with id ${userId}");

        return 0;
    }

    /**
     * @param string[] $propertyOptions
     *
     * @return string[]
     */
    private function parseProperties(array $propertyOptions): array
    {
        $properties = [];

        foreach ($propertyOptions as $propertyOption) {
            $values = explode('=', $propertyOption);
            $properties[$values[0]] = $values[1];
        }

        return $properties;
    }
}
