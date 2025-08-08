<?php

namespace RectorPrefix202305\Psr\Log;

/**
 * Describes a logger-aware instance.
 */
interface LoggerAwareInterface
{
    /**
     * Sets a logger instance on the object.
     */
<<<<<<<< HEAD:legacy/vendor/rector/rector/vendor/psr/log/src/LoggerAwareInterface.php
    public function setLogger(LoggerInterface $logger) : void;
========
    public function setLogger(LoggerInterface $logger): void;
>>>>>>>> master:api/vendor/psr/log/src/LoggerAwareInterface.php
}
