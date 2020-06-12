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

use Psr\Container\ContainerInterface;

/**
 * Provides the opportunity to run an asynchronous web server with some background tasks in PHP environment (concurrent
 * approach is based on event loop and non-blocking I/O). Uses implementation of PSR-11 container to manage
 * dependencies.
 */
class Application
{
    /**
     * Gives access to object instances (services)
     *
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * Application constructor.
     *
     * @param ContainerInterface $container Gives access to object instances (services)
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Starts ReactPHP event loop and socket server
     *
     * @return void
     */
    public function run(): void
    {
    }
}
