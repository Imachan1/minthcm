<?php

namespace RectorPrefix202305\Psr\Log;

/**
 * Describes a logger instance.
 *
 * The message MUST be a string or object implementing __toString().
 *
 * The message MAY contain placeholders in the form: {foo} where foo
 * will be replaced by the context data in key "foo".
 *
 * The context array can contain arbitrary data. The only assumption that
 * can be made by implementors is that if an Exception instance is given
 * to produce a stack trace, it MUST be in a key named "exception".
 *
 * See https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 * for the full interface specification.
 */
interface LoggerInterface
{
    /**
     * System is unusable.
     *
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
<<<<<<<< HEAD:legacy/vendor/rector/rector/vendor/psr/log/src/LoggerInterface.php
    public function emergency($message, array $context = []) : void;
========
    public function emergency(string|\Stringable $message, array $context = []);

>>>>>>>> master:legacy/vendor/psr/log/src/LoggerInterface.php
    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
<<<<<<<< HEAD:legacy/vendor/rector/rector/vendor/psr/log/src/LoggerInterface.php
    public function alert($message, array $context = []) : void;
========
    public function alert(string|\Stringable $message, array $context = []);

>>>>>>>> master:legacy/vendor/psr/log/src/LoggerInterface.php
    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
<<<<<<<< HEAD:legacy/vendor/rector/rector/vendor/psr/log/src/LoggerInterface.php
    public function critical($message, array $context = []) : void;
========
    public function critical(string|\Stringable $message, array $context = []);

>>>>>>>> master:legacy/vendor/psr/log/src/LoggerInterface.php
    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
<<<<<<<< HEAD:legacy/vendor/rector/rector/vendor/psr/log/src/LoggerInterface.php
    public function error($message, array $context = []) : void;
========
    public function error(string|\Stringable $message, array $context = []);

>>>>>>>> master:legacy/vendor/psr/log/src/LoggerInterface.php
    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
<<<<<<<< HEAD:legacy/vendor/rector/rector/vendor/psr/log/src/LoggerInterface.php
    public function warning($message, array $context = []) : void;
========
    public function warning(string|\Stringable $message, array $context = []);

>>>>>>>> master:legacy/vendor/psr/log/src/LoggerInterface.php
    /**
     * Normal but significant events.
     *
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
<<<<<<<< HEAD:legacy/vendor/rector/rector/vendor/psr/log/src/LoggerInterface.php
    public function notice($message, array $context = []) : void;
========
    public function notice(string|\Stringable $message, array $context = []);

>>>>>>>> master:legacy/vendor/psr/log/src/LoggerInterface.php
    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
<<<<<<<< HEAD:legacy/vendor/rector/rector/vendor/psr/log/src/LoggerInterface.php
    public function info($message, array $context = []) : void;
========
    public function info(string|\Stringable $message, array $context = []);

>>>>>>>> master:legacy/vendor/psr/log/src/LoggerInterface.php
    /**
     * Detailed debug information.
     *
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
<<<<<<<< HEAD:legacy/vendor/rector/rector/vendor/psr/log/src/LoggerInterface.php
    public function debug($message, array $context = []) : void;
========
    public function debug(string|\Stringable $message, array $context = []);

>>>>>>>> master:legacy/vendor/psr/log/src/LoggerInterface.php
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed   $level
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
<<<<<<<< HEAD:legacy/vendor/rector/rector/vendor/psr/log/src/LoggerInterface.php
    public function log($level, $message, array $context = []) : void;
========
    public function log($level, string|\Stringable $message, array $context = []);
>>>>>>>> master:legacy/vendor/psr/log/src/LoggerInterface.php
}
