<?php

/**
 * This file is part of the ramsey/collection library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace Ramsey\Collection\Exception;

<<<<<<<< HEAD:api/vendor/ramsey/collection/src/Exception/CollectionException.php
use Throwable;

interface CollectionException extends Throwable
========
use RuntimeException;

/**
 * Thrown when attempting to use a sort order that is not recognized.
 */
class InvalidSortOrderException extends RuntimeException
>>>>>>>> feature/146470:api/vendor/ramsey/collection/src/Exception/InvalidSortOrderException.php
{
}
