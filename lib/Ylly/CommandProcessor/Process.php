<?php
namespace Ylly\CommandProcessor;

/**
 * Abstract process class to be extended by
 * concrete implementation.
 *
 * @abstract
 * @author Daniel Leech <daniel@dantleech.com> 
 */
abstract class Process
{
    protected $commands = array();
    protected $output = null;
    protected $data = null;
    protected $title = null;
    protected $initialized = false;

    abstract protected function configure();

    public function init()
    {
        $this->initialized = true;
        $this->configure();
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setOutput($output)
    {
        if (!method_exists($output, 'write')) {
            throw new \InvalidArgumentException(sprintf('Output class "%s" does not implement "writeln" method',
                get_class($output)));
        }

        $this->output = $output;
    }

    public function writeOutput($message)
    {
        if ($this->output) {
            $this->output->write($message);
        }
    }

    public function addCommand(CommandInterface $command)
    {
        $command->setProcess($this);
        $this->commands[] = $command;
    }

    public function getCommand($name) 
    {
        foreach ($this->commands as $command) {
            if ($command->getName() == $name) {
                return $command;
            }
        }

        throw new \InvalidArgumentException(sprintf('Unknown command "%s"', $name));
    }
    
    public function getCommands()
    {
        return $this->commands;
    }
    
    public function removeCommand($name)
    {
        foreach ($this->commands as $key => $command) {
            if ($name == $command->getName()) {
                unset($this->commands[$key]);
                return;
            }
        }

        throw new \InvalidArgumentException(sprintf('Unknown command "%s"', $name));
    }

    public function execute()
    {
        if (!$this->initialized) {
            $this->init();
        }
        
        if ($title = $this->getTitle()) {
            $this->writeOutput(sprintf("\n%s\n", $title));
            for ($i = 0; $i < strlen($title); $i++) {
                $this->writeOutput('=');
            }
            $this->writeOutput("\n\n");
        }

        $this->writeOutput(sprintf("%d commands registered\n\n", count($this->commands)));

        foreach ($this->commands as $i => $command) {
            $this->writeOutput(sprintf('%d. Command [%s] %s', $i, $command->getName(), $command->getDescription()));
            $command->execute();
            $this->writeOutput(sprintf("  [OK]\n"));
        }
    }
}
