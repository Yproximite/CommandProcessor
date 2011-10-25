<?php

namespace Ylly\CommandProcessor\Tests;

use Ylly\CommandProcessor\ProcessCommand;

class ProcessCommandTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->process = $this->getMock('Ylly\CommandProcessor\Process', array('configure', 'doThis'));
        $this->command = new ProcessCommand('doThis', 'Do that');
        $this->command->setProcess($this->process);
    }

    public function testDescription()
    {
        $this->assertEquals('Do that', $this->command->getDescription());
    }

    public function testExecute()
    {
        $this->process->expects($this->once())
            ->method('doThis');

        $this->command->execute();
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetResultNotExecuted()
    {
        $this->command->getResult();
    }

    public function testGetResult()
    {
        $this->process->expects($this->once())
            ->method('doThis')
            ->will($this->returnValue('Foo'));

        $this->command->execute();
        $this->assertEquals('Foo', $this->command->getResult());
    }
}
