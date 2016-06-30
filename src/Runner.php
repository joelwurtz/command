<?php

namespace Joli\Command;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class Runner
{
    /**
     * Run a command
     *
     * @param Command $command Command to run
     * @param array   $options Specific options for the run
     *
     * @return RunningCommand
     */
    abstract public function run(Command $command, array $options = []);

    /**
     * Get default options
     *
     * @return OptionsResolver
     */
    protected function getOptionsResolver()
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefaults([
            'stdout' => STDOUT,
            'stderr' => STDERR,
            'stdin' => STDIN,
            'environments' => []
        ]);

        return $optionsResolver;
    }
}
