<?php

namespace Tests\Wrep\Daemonizable\Command;

use Acme\DemoBundle\Command\ExampleCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ExampleCommandTest extends TestCase
{
    public function setUp(): void
    {
        include_once('examples/ExampleCommand.php');
    }

    public function testDescriptionRenders(): void
    {
        $sut = new ExampleCommand();
        $this->assertEquals('acme:examplecommand', $sut->getName());
    }

    public function testExecutionOnceCase(): void
    {
        $sut = new ExampleCommand();

        // required otherwise the getContainer fails with a type error
        $mockContainer = $this->createMock(ContainerInterface::class);
        $sut->setContainer($mockContainer);

        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        // TODO make this true if `run-once`, instead of always true.
        $input->method('getOption')->willReturn(true);

        $this->assertSame(0, $sut->run($input, $output), 'Default exit code is not 0');

        // runs locally, not sure about ci
        $randomNumber = (int) file_get_contents('/tmp/acme-avg-score.txt');
        $this->assertIsInt($randomNumber);
    }
}
