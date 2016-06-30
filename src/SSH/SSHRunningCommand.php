<?php

namespace Joli\Command\SSH;

use Joli\Command\RunningCommand;

class SSHRunningCommand extends RunningCommand
{
    private $execResource;

    private $isFinished = false;

    private $exitCode = 0;

    public function __construct($execResource, array $commandStreams, array $localStreams)
    {
        parent::__construct($commandStreams, $localStreams);

        $this->execResource = $execResource;
    }

    public function wait()
    {
        if ($this->isFinished) {
            return $this->exitCode;
        }
        
    }
}
