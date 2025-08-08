<?php

namespace RectorPrefix202305\Psr\Log;

/**
 * Basic Implementation of LoggerAwareInterface.
 */
trait LoggerAwareTrait
{
    /**
     * The logger instance.
     *
     * @var LoggerInterface|null
     */
<<<<<<<< HEAD:legacy/vendor/rector/rector/vendor/psr/log/src/LoggerAwareTrait.php
    protected $logger;
========
    protected ?LoggerInterface $logger = null;

>>>>>>>> master:legacy/vendor/psr/log/src/LoggerAwareTrait.php
    /**
     * Sets a logger.
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger) : void
    {
        $this->logger = $logger;
    }
}
