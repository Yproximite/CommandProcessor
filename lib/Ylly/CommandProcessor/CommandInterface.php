<?php

namespace Ylly\CommandProcessor;
use Ylly\CommandProcessor\Process;

interface CommandInterface
{
    /**
     * Execute the command
     *
     * @access public
     * @return void
     */
    public function execute();

    /**
     * Set the executing process ...
     *
     * @param Process $process 
     * @access public
     * @return void
     */
    public function setProcess(Process $process);

    /**
     * To return a short description of the command
     *
     * @access public
     * @return string
     */
    public function getDescription();

    /**
     * Return the title of this command
     *
     * @access public
     * @return string
     */
    public function getTitle();

    /**
     * If the command has been executed or not
     *
     * @access public
     * @return boolean 
     */
    public function hasExecuted();

    /**
     * If the command has been executed, this
     * will return the result of the command.
     *
     * @access public
     * @return void
     */
    public function getResult();
}
