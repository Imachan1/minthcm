<?php

namespace Psr\Log;

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
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
     * @param string|\Stringable $message
========
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php
     * @param mixed[] $context
     */
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
    public function emergency(string|\Stringable $message, array $context = []);
========
    public function emergency(string|\Stringable $message, array $context = []): void;
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
     * @param string|\Stringable $message
========
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php
     * @param mixed[] $context
     */
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
    public function alert(string|\Stringable $message, array $context = []);
========
    public function alert(string|\Stringable $message, array $context = []): void;
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
     * @param string|\Stringable $message
========
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php
     * @param mixed[] $context
     */
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
    public function critical(string|\Stringable $message, array $context = []);
========
    public function critical(string|\Stringable $message, array $context = []): void;
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
     * @param string|\Stringable $message
========
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php
     * @param mixed[] $context
     */
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
    public function error(string|\Stringable $message, array $context = []);
========
    public function error(string|\Stringable $message, array $context = []): void;
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
     * @param string|\Stringable $message
========
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php
     * @param mixed[] $context
     */
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
    public function warning(string|\Stringable $message, array $context = []);
========
    public function warning(string|\Stringable $message, array $context = []): void;
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php

    /**
     * Normal but significant events.
     *
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
     * @param string|\Stringable $message
========
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php
     * @param mixed[] $context
     */
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
    public function notice(string|\Stringable $message, array $context = []);
========
    public function notice(string|\Stringable $message, array $context = []): void;
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
     * @param string|\Stringable $message
========
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php
     * @param mixed[] $context
     */
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
    public function info(string|\Stringable $message, array $context = []);
========
    public function info(string|\Stringable $message, array $context = []): void;
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php

    /**
     * Detailed debug information.
     *
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
     * @param string|\Stringable $message
========
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php
     * @param mixed[] $context
     */
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
    public function debug(string|\Stringable $message, array $context = []);
========
    public function debug(string|\Stringable $message, array $context = []): void;
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php

    /**
     * Logs with an arbitrary level.
     *
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
     * @param mixed   $level
     * @param string|\Stringable $message
========
     * @param mixed $level
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php
     * @param mixed[] $context
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
<<<<<<<< HEAD:legacy/vendor/psr/log/src/LoggerInterface.php
    public function log($level, string|\Stringable $message, array $context = []);
========
    public function log($level, string|\Stringable $message, array $context = []): void;
>>>>>>>> master:api/vendor/psr/log/src/LoggerInterface.php
}
