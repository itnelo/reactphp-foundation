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

namespace Foundation;

use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;

/**
 * Encapsulates graceful shutdown logic; use this service whenever you want to terminate application at some custom
 * execution point.
 */
class Shutdown
{
    /**
     * Performs logging for cleanup procedures
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Event loop
     *
     * @var LoopInterface
     */
    private LoopInterface $loop;

    /**
     * Shutdown constructor.
     *
     * @param LoggerInterface $logger Performs logging for cleanup procedures
     * @param LoopInterface   $loop   Event loop
     */
    public function __construct(LoggerInterface $logger, LoopInterface $loop)
    {
        $this->logger = $logger;
        $this->loop   = $loop;
    }

    /**
     * Stops the event loop and performs any other cleanup routines
     *
     * @return void
     */
    public function execute(): void
    {
        $this->logger->info('Stopping event loop...');

        $this->loop->addTimer(0.5, fn() => $this->loop->stop());
    }

    /**
     * Performs proxy pass to the shutdown execution logic
     *
     * @return void
     */
    public function __invoke()
    {
        $this->execute();
    }
}
