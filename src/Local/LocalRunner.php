<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wurtz
 * Date: 17/06/2016
 * Time: 02:11
 */

namespace Joli\Command\Local;

use Joli\Command\Command;
use Joli\Command\Runner;

class LocalRunner extends Runner
{
    /**
     * {@inheritdoc}
     */
    public function run(Command $command, array $options = [])
    {
        $options = $this->getOptionsResolver()->resolve($options);
        $stdout = $options['stdout'];
        $stderr = $options['stderr'];
        $stdin = $options['stdin'];
        $pipes = [];

        $localExec = proc_open((string) $command, [
            $stdin,
            $stdout,
            $stderr,
        ], $pipes, null, $options['environments']);
    }
}
