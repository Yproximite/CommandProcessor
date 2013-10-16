<?php

namespace Ylly\CommandProcessor;

use Ylly\CommandProcessor\Process;

/**
 * Command which executes a method
 * on the class given.
 *
 * @uses Command
 * @package 
 * @author Daniel Leech <daniel@dantleech.com> 
 */
class ProcessCommand implements CommandInterface
{
    protected $methodString;
    protected $description;
    protected $process;
    protected $result;
    protected $hasExecuted = false;

    public function __construct($methodString, $description)
    {
        $this->methodString = $methodString;
        $this->description = $description;
    }

    public function setProcess(Process $process)
    {
        $this->process = $process;
    }

    public function getName()
    {
        return $this->methodString;
    }

    public function getTitle()
    {
        return $this->getName();
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function execute()
    {
        if (!method_exists($this->process, $method = $this->methodString)) {
            throw new \InvalidArgumentException(
                sprintf('Method "%s" does not exist in process "%s"', $this->methodString, get_class($this->process)));
        }

        $this->result = $this->process->$method($this);
        $this->hasExecuted = true;
    }

    public function hasExecuted()
    {
        return $this->hasExecuted;
    }

    public function getResult()
    {
        if (!$this->hasExecuted()) {
            throw new \LogicException(sprintf('Cannot get result for command "%s". Command has not been executed yet.',
                $this->methodString));
        }

        return $this->result;
    }
}
