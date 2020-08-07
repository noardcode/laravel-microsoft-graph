<?php

namespace Noardcode\MicrosoftGraph\Traits;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Noardcode\MicrosoftGraph\ValueObjects\AccessToken;
use Noardcode\MicrosoftGraph\ValueObjects\AuthorizationResponse;
use RuntimeException;

/**
 * Trait HasOauth2
 * @package Noardcode\MicrosoftGraph\Traits
 */
trait HasOauth2
{
    /**
     * @var string|null
     */
    protected ?string $endpoint = null;

    /**
     * Redirect the user to the Azure AD authorization page.
     * Provide a unique/random string to prevent cross-site request forgery attacks.
     *
     * @param string $state
     * @return void
     */
    public function authorize(string $state): void
    {
        redirect()->to($this->getEndpoint() . '/authorize?' . http_build_query([
            'client_id' => config('microsoft-graph.client_id'),
            'response_type' => 'code',
            'redirect_uri' => config('app.url') . config('microsoft-graph.oauth2.redirect_uri'),
            'scope' => config('microsoft-graph.oauth2.permissions'),
            'response_mode' => 'query',
            'state' => $state // Unique string to prevent cross-site request forgery attacks.
        ]))->send();
    }

    /**
     * @param AuthorizationResponse $authorizationResponse
     * @param string $state
     * @return AccessToken
     * @throws InvalidArgumentException
     */
    public function requestAccessToken(AuthorizationResponse $authorizationResponse, string $state): AccessToken
    {
        if ($authorizationResponse->getState() != $state) {
            throw new InvalidArgumentException('Invalid state.');
        }

        $response = Http::asForm()->post(
            $this->getEndpoint() . '/token',
            [
                'client_id' => config('microsoft-graph.client_id'),
                'grant_type' => 'authorization_code',
                'code' => $authorizationResponse->getCode(),
                'scope' => config('microsoft-graph.oauth2.permissions'),
                'redirect_uri' => config('app.url') . config('microsoft-graph.oauth2.redirect_uri'),
                'client_secret' => config('microsoft-graph.client_secret')
            ]
        );

        if ($response->failed()) {
            throw new RuntimeException('Could not retrieve access token.');
        }

        return new AccessToken(json_decode($response, true));
    }

    /**
     * @param AccessToken $accessToken
     * @return AccessToken
     */
    public function refreshToken(AccessToken $accessToken): AccessToken
    {
        $response = Http::asForm()->post(
            $this->getEndpoint() . '/token',
            [
                'client_id' => config('microsoft-graph.client_id'),
                'grant_type' => 'refresh_token',
                'refresh_token' => $accessToken->getRefreshToken(),
                'scope' => config('microsoft-graph.oauth2.permissions'),
                'redirect_uri' => config('app.url') . config('microsoft-graph.oauth2.redirect_uri'),
                'client_secret' => config('microsoft-graph.client_secret')
            ]
        );

        if ($response->failed()) {
            throw new RuntimeException('Could not retrieve access token.');
        }

        return new AccessToken(json_decode($response, true));
    }

    /**
     * @return string
     */
    private function getEndpoint(): string
    {
        if (is_null($this->endpoint)) {
            $this->endpoint = str_replace(
                '[TENANT_ID]',
                config('microsoft-graph.tenant_id'),
                config('microsoft-graph.oauth2.endpoint')
            );
        }

        return $this->endpoint;
    }
}
