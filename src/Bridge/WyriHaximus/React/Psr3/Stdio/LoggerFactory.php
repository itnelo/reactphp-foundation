<?php

/*
 * This file is part of the ReactPHP Foundation <https://github.com/itnelo/reactphp-foundation>.
 *
 * (c) 2020 Pavel Petrov <itnelo@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/mit MIT
 */

declare(strict_types=1);

namespace Foundation\Bridge\WyriHaximus\React\Psr3\Stdio;

use React\Stream\WritableStreamInterface;
use WyriHaximus\React\PSR3\Stdio\StdioLogger;

/**
 * Configures and instantiates an StdioLogger object without redundant clone operations
 *
 * TODO: this factory is a temporary/prototype solution due to poor design of Stdio library, should be replaced later
 */
class LoggerFactory
{
    /**
     * Returns configured StdioLogger instance
     *
     * @param WritableStreamInterface $stream
     * @param bool                    $hideLevel
     * @param bool                    $newLine
     *
     * @return StdioLogger
     */
    public static function getLogger(
        WritableStreamInterface $stream,
        bool $hideLevel = false,
        bool $newLine = false
    ): StdioLogger {
        $logger = new StdioLogger($stream);

        $logger = $logger->withHideLevel($hideLevel);
        $logger = $logger->withNewLine($newLine);

        return $logger;
    }
}
