<?php

namespace Noardcode\MicrosoftGraph\Endpoints;

use Exception;
use Noardcode\MicrosoftGraph\Collections\UsersCollection;
use Noardcode\MicrosoftGraph\MicrosoftGraphClient;
use Noardcode\MicrosoftGraph\ValueObjects\Users\User;

/**
 * Class UsersEndpoint
 * @package Noardcode\MicrosoftGraph\Endpoints
 */
class UsersEndpoint
{
    /**
     * @var MicrosoftGraphClient
     */
    protected MicrosoftGraphClient $client;

    /**
     * UserEndpoint constructor.
     * @param MicrosoftGraphClient $client
     */
    public function __construct(MicrosoftGraphClient $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieve the profiles of all organisation users.
     *
     * @return UsersCollection
     * @throws Exception
     */
    public function all(): UsersCollection
    {
        $users = $this->client->get(
            '/users',
            [],
            User::class
        );

        return new UsersCollection($users);
    }

    /**
     * Retrieve the profile of a single user.
     * If no user (gu)id is provided the profile of the logged in user will be returned.
     *
     * @param string|null $userId
     * @return User
     * @throws Exception
     */
    public function get(string $userId = null): User
    {
        return $this->client->get(
            $this->getUserPath($userId),
            [],
            User::class
        );
    }

    /**
     * @param string|null $userId
     * @return string
     */
    private function getUserPath(string $userId = null): string
    {
        return is_null($userId) ? '/me' : '/users/' . $userId;
    }
}
