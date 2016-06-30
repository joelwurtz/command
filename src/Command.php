<?php

namespace Joli\Command;

abstract class Command
{
    const ESCAPE = '"';

    /**
     * Return path of the binary
     *
     * @return string
     */
    abstract public function getBinaryPath();

    /**
     * Get a list of arguments for the command
     *
     * @return string[]
     */
    abstract public function getArgs();

    /**
     * @return string
     */
    public function __toString()
    {
        return implode(' ', array_merge([$this->getBinaryPath()], array_map(function ($arg) {
            return self::ESCAPE . addcslashes($arg, self::ESCAPE) . self::ESCAPE;
        }, $this->getArgs())));
    }
}
