<?php

namespace Directory\Tests\UI;

use Directory\Infrastructure\Symfony\TestKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseFunctionalTest extends TestCase
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * @var TestKernel
     */
    private $kernel;

    public function setUp(): void
    {
        parent::setUp();
        $this->kernel = new TestKernel('test', false);
        $this->application = new Application($this->kernel);

        // Initialize database
        $this->executeCommand('migrations:migrate');
    }

    /**
     * @param string[] $args
     *
     * @return string
     */
    protected function executeCommand(string $commandName, array $args = [])
    {
        $command = $this->application->find($commandName);
        $commandTester = new CommandTester($command);
        $commandTester->execute($args, ['interactive' => false]);

        return $commandTester->getDisplay();
    }

    protected function executeRequest(Request $request): Response
    {
        return $this->kernel->handle($request);
    }

    protected function json(Response $response): array
    {
        return json_decode($response->getContent(), true);
    }
}
