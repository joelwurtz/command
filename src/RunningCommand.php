<?php

namespace Joli\Command;

abstract class RunningCommand
{
    const STDIN_STREAM = 0;
    const STDOUT_STREAM = 1;
    const STDERR_STREAM = 2;

    protected $commandStreams;

    protected $localStreams;

    public function __construct(array $commandStreams, array $localStreams)
    {
        $this->commandStreams = $commandStreams;
        $this->localStreams = $localStreams;
    }

    /**
     * Get a stream of the command
     *
     * @param int $type Type of stream to get, one of RunningCommand::STDIN_STREAM, RunningCommand::STDOUT_STREAM or RunningCommand::STDERR_STREAM
     *
     * @return resource
     */
    public function getCommandStream($type)
    {
        return $this->commandStreams[$type];
    }

    /**
     * Get a local stream (the one specified in the run command)
     *
     * @param int $type Type of stream to get, one of RunningCommand::STDIN_STREAM, RunningCommand::STDOUT_STREAM or RunningCommand::STDERR_STREAM
     *
     * @return resource
     */
    public function getLocalStream($type)
    {
        return $this->localStreams[$type];
    }

    /**
     * Wait for comamnd to finish and return exit code
     *
     * @return int The exit code
     */
    abstract public function wait();
}
