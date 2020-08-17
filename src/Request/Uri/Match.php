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

namespace Foundation\Request\Uri;

/**
 * Holds information which action to perform to generate a response for the given request
 */
class Match
{
    /**
     * Action name to generate a response for the given request
     *
     * @var string|null
     */
    private ?string $actionName;

    /**
     * Match constructor.
     */
    public function __construct()
    {
        $this->actionName = null;
    }

    /**
     * Returns action name to generate a response for the given request
     *
     * @return string|null
     */
    public function getActionName(): ?string
    {
        return $this->actionName;
    }

    /**
     * Sets action name to generate a response for the given request
     *
     * @param string $actionName Action name
     *
     * @return void
     */
    public function setActionName(string $actionName): void
    {
        $this->actionName = $actionName;
    }
}
