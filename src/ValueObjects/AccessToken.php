<?php

namespace Noardcode\MicrosoftGraph\ValueObjects;

use Carbon\Carbon;
use InvalidArgumentException;

/**
 * Class AccessToken
 * @package Noardcode\MicrosoftGraph\ValueObjects
 */
class AccessToken
{
    /**
     * @var mixed|string
     */
    protected string $tokenType;

    /**
     * @var mixed|string
     */
    protected string $scope;

    /**
     * @var Carbon
     */
    protected Carbon $expiresAt;

    /**
     * @var mixed|string
     */
    protected string $accessToken;

    /**
     * @var mixed|string
     */
    protected string $refreshToken;

    /**
     * AccessToken constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        if (empty($parameters['token_type'])) {
            throw new InvalidArgumentException('Required option "token_type" is not provided.');
        }

        if (empty($parameters['scope'])) {
            throw new InvalidArgumentException('Required option "scope" is not provided.');
        }

        if (empty($parameters['expires_in']) || !is_numeric($parameters['expires_in'])) {
            throw new InvalidArgumentException('Required option "expires_in" is not provided or not numeric.');
        }

        if (empty($parameters['access_token'])) {
            throw new InvalidArgumentException('Required option "access_token" is not provided.');
        }

        if (empty($parameters['refresh_token'])) {
            throw new InvalidArgumentException('Required option "refresh_token" is not provided.');
        }

        $this->tokenType = $parameters['token_type'];
        $this->scope = $parameters['scope'];
        $this->expiresAt = Carbon::now()->addSeconds($parameters['expires_in']);
        $this->accessToken = $parameters['access_token'];
        $this->refreshToken = $parameters['refresh_token'];
    }

    /**
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @return Carbon
     */
    public function getExpiresAt(): Carbon
    {
        return $this->expiresAt;
    }

    /**
     * @return mixed|string
     */
    public function getToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return mixed|string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @return bool
     */
    public function hasExpired(): bool
    {
        return Carbon::now() > $this->expiresAt;
    }
}
