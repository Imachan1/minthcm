<?php

namespace RectorPrefix202305\Psr\Log;

/**
 * This Logger can be used to avoid conditional log calls.
 *
 * Logging should always be optional, and if no logger is provided to your
 * library creating a NullLogger instance to have something to throw logs at
 * is a good way to avoid littering your code with `if ($this->logger) { }`
 * blocks.
 */
class NullLogger extends AbstractLogger
{
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string|\Stringable $message
     * @param array $context
     *
     * @return void
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
<<<<<<<< HEAD:legacy/vendor/rector/rector/vendor/psr/log/src/NullLogger.php
    public function log($level, $message, array $context = []) : void
========
    public function log($level, string|\Stringable $message, array $context = [])
>>>>>>>> master:legacy/vendor/psr/log/src/NullLogger.php
    {
        // noop
    }
}
