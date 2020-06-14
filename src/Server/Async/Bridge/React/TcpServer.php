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

namespace Foundation\Server\Async\Bridge\React;

use Foundation\ServerInterface;
use React\Http\StreamingServer;
use React\Socket\TcpServer as ReactTcpServer;

/**
 * Handles HTTP requests in concurrent approach using TCP/IP server implementation from ReactPHP package
 */
class TcpServer implements ServerInterface
{
    /**
     * Accepts plaintext TCP/IP connections
     *
     * @var ReactTcpServer
     */
    private ReactTcpServer $socket;

    /**
     * @var StreamingServer
     */
    private StreamingServer $server;

    /**
     * TcpServer constructor.
     *
     * @param StreamingServer $server Process incoming HTTP requests
     * @param ReactTcpServer  $socket Accepts plaintext TCP/IP connections
     */
    public function __construct(StreamingServer $server, ReactTcpServer $socket)
    {
        $this->server = $server;
        $this->socket = $socket;
    }

    /**
     * {@inheritDoc}
     */
    public function up(): void
    {
        $this->server->listen($this->socket);
    }
}
