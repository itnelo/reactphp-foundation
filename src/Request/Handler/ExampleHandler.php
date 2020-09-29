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

namespace Foundation\Request\Handler;

use Exception;
use Foundation\Request\HandlerInterface;
use Foundation\Request\Uri\Match;
use Foundation\Request\Uri\MatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Psr\Log\LoggerInterface;
use React\Http\Message\Response;

/**
 * Request handler example
 */
class ExampleHandler implements HandlerInterface
{
    /**
     * Logs handler events
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Finds context to decide which action should be used to generate a response for the given request
     *
     * @var MatcherInterface
     */
    private MatcherInterface $uriMatcher;

    /**
     * Handler's unique identifier
     *
     * @var string
     */
    private static string $uid;

    /**
     * ExampleHandler constructor.
     *
     * @param LoggerInterface  $logger     Logs handler events
     * @param MatcherInterface $uriMatcher Finds context to decide which action should be used to generate a response
     *                                     for the given request
     *
     * @throws Exception
     */
    public function __construct(LoggerInterface $logger, MatcherInterface $uriMatcher)
    {
        $this->logger     = $logger;
        $this->uriMatcher = $uriMatcher;

        self::$uid = bin2hex(random_bytes(4));
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(RequestInterface $request): ResponseInterface
    {
        $this->logger->debug('An HTTP request has been received.');

        $requestUri     = $request->getUri();
        $requestUriPath = $requestUri->getPath();

        $uriMatch = $this->uriMatcher->match($requestUriPath);

        if (!$uriMatch instanceof Match) {
            $response = $this->getNotFoundResponse();
        } else {
            // $uriMatch->getActionName();

            $response = $this->getContentResponse();
        }

        return $response;
    }

    /**
     * Returns response for the case when a given URI is found
     *
     * @return Response
     */
    private function getContentResponse(): Response
    {
        $responseBody = json_encode(
            [
                'uid' => self::$uid,
                'ts'  => time(),
            ]
        );

        $response = new Response(
            200,
            [
                'Content-Type' => 'application/json; charset=utf-8',
            ],
            $responseBody
        );

        return $response;
    }

    /**
     * Returns response for the case when a given URI is not found
     *
     * @return Response
     */
    private function getNotFoundResponse(): Response
    {
        $responseBody = json_encode(
            [
                'errors' => [
                    [
                        'message' => 'Resource is not found.'
                    ],
                ],
            ]
        );

        $response = new Response(
            404,
            [
                'Content-Type' => 'application/json; charset=utf-8',
            ],
            $responseBody
        );

        return $response;
    }
}
