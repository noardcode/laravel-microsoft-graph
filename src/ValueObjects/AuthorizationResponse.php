<?php

namespace Noardcode\MicrosoftGraph\ValueObjects;

use InvalidArgumentException;

/**
 * Class AuthorizationResponse
 * @package Noardcode\MicrosoftGraph\ValueObjects
 */
class AuthorizationResponse
{
    /**
     * @var string
     */
    protected string $code;

    /**
     * @var string
     */
    protected string $state;

    /**
     * @var string|null
     */
    protected ?string $sessionState;

    /**
     * AuthorizationResponse constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        if (empty($parameters['code'])) {
            throw new InvalidArgumentException('Required parameter "code" is not provided.');
        }

        if (empty($parameters['state'])) {
            throw new InvalidArgumentException('Required parameter "state" is not provided.');
        }

        $this->code = $parameters['code'];
        $this->state = $parameters['state'];
        $this->sessionState = $parameters['session_state'];
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return string|null
     */
    public function sessionState(): ?string
    {
        return $this->sessionState;
    }
}
