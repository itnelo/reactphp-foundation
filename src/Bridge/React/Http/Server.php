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

namespace Foundation\Bridge\React\Http;

use Exception;
use Foundation\ServerInterface;
use Psr\Log\LoggerInterface;
use React\Http\Server as HttpServer;
use React\Socket\TcpServer;

/**
 * Handles HTTP requests in concurrent approach using TCP/IP server implementation from ReactPHP package
 */
class Server implements ServerInterface
{
    /**
     * Logs information about server interactions
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Handles incoming HTTP requests using specified connection manager
     *
     * @var HttpServer
     */
    private HttpServer $server;

    /**
     * Accepts plaintext TCP/IP connections
     *
     * @var TcpServer
     */
    private TcpServer $socket;

    /**
     * Server constructor.
     *
     * @param LoggerInterface $logger Logs information about server interactions
     * @param HttpServer      $server Handles incoming HTTP requests using specified connection manager
     * @param TcpServer       $socket Accepts plaintext TCP/IP connections
     */
    public function __construct(LoggerInterface $logger, HttpServer $server, TcpServer $socket)
    {
        $this->logger = $logger;
        $this->server = $server;
        $this->socket = $socket;
    }

    /**
     * {@inheritDoc}
     *
     * // TODO: remove all context placeholders from log messages (after temp logger is removed).
     */
    public function up(): void
    {
        $this->server->listen($this->socket);

        $this->server->on(
            'error',
            function (Exception $exception) {
                $exceptionCode    = $exception->getCode();
                $exceptionMessage = $exception->getMessage();

                $this->logger->error(
                    'An error has been occurred during request processing: ({exceptionCode}){exceptionMessage}',
                    [
                        'exceptionCode'    => $exceptionCode,
                        'exceptionMessage' => $exceptionMessage,
                    ]
                );
            }
        );

        $socketAddress = $this->socket->getAddress();

        $this->logger->info(
            'Listening incoming TCP/IP connections on {socketAddress}.',
            [
                'socketAddress' => $socketAddress,
            ]
        );
    }
}
