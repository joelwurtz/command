<?php

namespace Joli\Command;
use Joli\Command\Exception\InvalidStreamException;
use Joli\Command\Exception\SynchronizeException;
use Joli\Command\Exception\TimeoutException;

/**
 * Synchronize multiple commands and wait for them to finish
 */
class Synchronize
{
    /**
     * Timeout in seconds for the max waiting time when synchronizing commands.
     *
     * @var int
     */
    private $timeout;

    public function __construct($timeout = 60)
    {
        $this->timeout = $timeout;
    }

    /**
     * Synchronize multiple commands
     *
     * @param RunningCommand[] ...$commands
     */
    public function synchronize(RunningCommand ...$commands)
    {
        $readStreams = [];
        $resourceIndexProcessStdout = [];
        $resourceIndexProcessStderr = [];
        $resourceIndexLocalStdin = [];

        foreach ($commands as $command) {
            $resourceIndexProcessStdout[$this->getResourceId($command->getCommandStream(RunningCommand::STDOUT_STREAM))] = $command;
            $resourceIndexProcessStderr[$this->getResourceId($command->getCommandStream(RunningCommand::STDERR_STREAM))] = $command;
            $resourceIndexLocalStdin[$this->getResourceId($command->getLocalStream(RunningCommand::STDIN_STREAM))] = $command;

            $readStreams[] = $command->getCommandStream(RunningCommand::STDOUT_STREAM);
            $readStreams[] = $command->getCommandStream(RunningCommand::STDERR_STREAM);
            $readStreams[] = $command->getLocalStream(RunningCommand::STDIN_STREAM);
        };

        $readStreams = array_unique($readStreams);
        $writeStreams = $expectStreams = [];
        
        while (count($readStreams) > 0) {
            $toReadStreams = clone $readStreams;
            $nbChangedStream = stream_select($toReadStreams, $writeStreams, $expectStreams, $this->timeout);

            if (false === $nbChangedStream) {
                throw new SynchronizeException();
            }
            
            if (0 === $nbChangedStream) {
                throw new TimeoutException();
            }

            foreach ($toReadStreams as $stream) {
                fread($stream, 8192);

                if (feof($stream)) {
                    unset($readStreams[array_search($stream, $readStreams)]);

                    if (isset($resourceIndexProcessStdout[$this->getResourceId($stream)])) {
                        unset($resourceIndexProcessStdout[$this->getResourceId($stream)]);
                    }

                    if (isset($resourceIndexProcessStderr[$this->getResourceId($stream)])) {
                        unset($resourceIndexProcessStderr[$this->getResourceId($stream)]);
                    }

                    if (!isset($resourceIndexProcessStdout[$this->getResourceId($stream)]) && !isset($resourceIndexProcessStderr[$this->getResourceId($stream)])) {
                        
                    }
                }
            }
        }
    }

    /**
     * Return the resource identifier
     *
     * @param resource $resource
     *
     * @return string
     */
    private function getResourceId($resource) {
        if (!preg_match('/Resource id #(.+?)$/', (string)$resource, $matches)) {
            throw new InvalidStreamException();
        }

        return $matches[1];
    }
}
