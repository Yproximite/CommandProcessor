<?php

namespace Ylly\CommandProcessor\Tests;

use Ylly\CommandProcessor\Process;

class MockProcess extends Process
{
    protected $phpunit;

    public function __construct($phpunit)
    {
        $this->phpunit = $phpunit;
    }

    protected function configure()
    {
        $command1 = $this->phpunit->getMock('Ylly\CommandProcessor\CommandInterface');
        $command1->expects($this->phpunit->once())
            ->method('execute');
        $command1->expects($this->phpunit->once())
            ->method('setProcess');
        $command2 = $this->phpunit->getMock('Ylly\CommandProcessor\CommandInterface');
        $command2->expects($this->phpunit->once())
            ->method('execute');

        $this->addCommand($command1);
        $this->addCommand($command2);
    }
}

class ProcessTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->process = new MockProcess($this);
        $this->process->setData(array(
            'key1' => 'value1',
            'key2' => 'value2',
        ));
    }

    public function testExecute()
    {
        $this->process->init();
        $this->process->execute();
    }
}
