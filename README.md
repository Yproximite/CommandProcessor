Command Processor
=================

This library provides a framework to enable the sequential and orderly execution of multi-stage processes.

Basic Usage
-----------

An example process::

    <?php

    namespace My\Namespace;
    use Ylly\CommandProcessor\Process;
    use Ylly\CommandProcessor\ProcessCommand;

    class MyProcess extends Process
    {
        public function configure()
        {
            $this->setTitle('My Hello World Process');
            $this->addCommand(new ProcessCommand('doHello', 'Says hello'));
            $this->addCommand(new ProcessCommand('doGoodbye', 'Says goodbye'));
        }

        public function doHello(CommandInterface $command)
        {
            echo "Hello world.";
        }

        public function doGoodbye(CommandInterface $command)
        {
            echo "Goodbye cruel world.";
            throw new \Exception('Process will fail gracefully when exception is encountered.');
        }
    }

The process can then be executed as follows::

    $p = new MyProcess;
    $p->execute();

Logging
-------

You can add logging to the process by passing a class that implements a `write` method.

    $logger = new MyLogger; // (must have a method `function write($string)`
    $p = new MyProcess;
    $p->setOutput($logger);
    $p->execute();

Custom Commands
---------------

The packaged `ProcessCommand` executes methods (which act as commands) defined within the process. However you can of course implement a class per command if you wish::

    <?php

    namespace My\Namespace;
    use Ylly\CommandProcessor\Process;
    use Ylly\CommandProcessor\CommandInterface;

    class MyCommand implements CommandInterface
    {
        // store result (optional)
        protected $result;

        // store the process (optional)
        protected $process;

        // store execution status
        protected $hasExecuted;

        /**
         * Execute the command
         *
         * @access public
         * @return void
         */
        public function execute()
        {
            $result = // perform some crazy task;

            // store the result for future access via. the `getResult` method.
            $this->result = $result;

            // mark the command as executed, quieried via. the `hasExecuted` method.
            $this->hasExecuted = true;
        }

        /**
         * Set the executing process ...
         *
         * @param Process $process 
         * @access public
         * @return void
         */
        public function setProcess(Process $process)
        {
            // store the process thats executing this command, if you like.
            $this->process = $process;
        }

        /**
         * To return a short description of the command
         *
         * @access public
         * @return string
         */
        public function getDescription()
        {
            return 'My command does this';
        }

        /**
         * Return the title of this command
         *
         * @access public
         * @return string
         */
        public function getTitle()
        {
            return 'My Command';
        }

        /**
         * If the command has been executed or not
         *
         * @access public
         * @return boolean 
         */
        public function hasExecuted()
        {
            return $this->hasExecuted;
        }

        /**
         * If the command has been executed, this
         * will return the result of the command if applicable
         *
         * @access public
         * @return void
         */
        public function getResult()
        {
            if (!$this->hasExecuted()) {
                throw new \Exception('Command has not been executed yet!');
            }

            return $this->result;
        }
    }

Alternatively you can override the `ProcessCommand` class.

Adding commands at runtime
--------------------------

You can dynamically add commands to the process using the `Process::addCommand` method::

    $p = new MyProcess;
    $p->addCommand(new AnotherCommand);
    $p->execute();

Passing data and dependencies to the process
--------------------------------------------

The abstract `Process` class has no constructor method, so you can create your own constructor, example::

    class MyProcess extends Process
    {
        public function __construct(Connection $dbconnection, SomeAPI $api)
        {
            // ..
        }
    }

User data should be passed to process through the `setData` method::

    $p = new MyProcess;
    $p->setData(array('Foo', 'Bar'));
    $p->execute();
