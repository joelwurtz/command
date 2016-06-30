<?php

namespace Joli\Command\SSH;

use Joli\Command\Command;
use Joli\Command\Runner;

/**
 * Run a command using SSH.
 */
class SSHRunner extends Runner
{
    private $session;

    public function __construct($session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function run(Command $command, array $options = [])
    {
        $options = $this->getOptionsResolver()->resolve($options);
        $stdout = $options['stdout'];
        $stderr = $options['stderr'];
        $stdin = $options['stdin'];

        $tty = null;
        $width = null;
        $height = null;

        if (posix_isatty($stdout) && posix_isatty($stderr)) {
            $tty = "ansi";
        }

        $exec = ssh2_exec($this->session, (string)$command, $tty, $options['environments'], $width, $height);
        $stderrExec = ssh2_fetch_stream($exec, SSH2_STREAM_STDERR);
        $stdioExec = ssh2_fetch_stream($exec, SSH2_STREAM_STDIO);

        return new SSHRunningCommand($exec, [
            SSHRunningCommand::STDOUT_STREAM => $stdioExec,
            SSHRunningCommand::STDERR_STREAM => $stderrExec,
            SSHRunningCommand::STDIN_STREAM => $stdioExec,
        ], [
            SSHRunningCommand::STDOUT_STREAM => $stdout,
            SSHRunningCommand::STDERR_STREAM => $stderr,
            SSHRunningCommand::STDIN_STREAM => $stdin,
        ]);
    }
}
