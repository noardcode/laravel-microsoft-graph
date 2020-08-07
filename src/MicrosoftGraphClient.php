<?php

namespace Noardcode\MicrosoftGraph;

use Exception;
use Microsoft\Graph\Graph;
use Noardcode\MicrosoftGraph\Endpoints\UsersEndpoint;
use Noardcode\MicrosoftGraph\Traits\HasOauth2;
use Noardcode\MicrosoftGraph\ValueObjects\AccessToken;

/**
 * Class MicrosoftGraphClient
 * @package Noardcode\MicrosoftGraph
 */
class MicrosoftGraphClient
{
    use HasOauth2;

    /**
     * @var AccessToken|null
     */
    protected ?AccessToken $accessToken;

    /**
     * @var Graph
     */
    public Graph $graph;

    /**
     * MicrosoftGraphClient constructor.
     * @param AccessToken|null $accessToken
     */
    public function __construct(?AccessToken $accessToken = null)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @param AccessToken $accessToken
     */
    public function setAccessToken(AccessToken $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return AccessToken
     */
    public function getAccessToken(): AccessToken
    {
        return $this->accessToken;
    }

    /**
     * @return Graph
     * @throws Exception
     */
    public function getGraph(): Graph
    {
        if (empty($this->accessToken)) {
            throw new Exception('No access token available.');
        }

        if (!isset($this->graph)) {
            $this->graph = new Graph();
            $this->graph->setAccessToken($this->accessToken->getToken());
        }

        return $this->graph;
    }

    /**
     * @return UsersEndpoint
     */
    public function users(): UsersEndpoint
    {
        return new UsersEndpoint($this);
    }

    /**
     * @param string $url
     * @param array $parameters
     * @param string|null $returnType
     * @return mixed
     * @throws Exception
     */
    public function get(string $url, array $parameters = [], string $returnType = null)
    {
        return $this->request('GET', $url, $parameters, $returnType);
    }

    /**
     * @param string $url
     * @param array $parameters
     * @return mixed
     * @throws Exception
     */
    public function post(string $url, array $parameters = [])
    {
        return $this->request('POST', $url, $parameters);
    }

    /**
     * @param string $url
     * @param array $parameters
     * @return mixed
     * @throws Exception
     */
    public function put(string $url, array $parameters = [])
    {
        return $this->request('PUT', $url, $parameters);
    }

    /**
     * @param string $url
     * @param array $parameters
     * @return mixed
     * @throws Exception
     */
    public function delete(string $url, array $parameters = [])
    {
        return $this->request('DELETE', $url, $parameters);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $parameters
     * @param string|null $returnType
     * @return mixed
     * @throws Exception
     */
    private function request(string $method, string $path, array $parameters = [], string $returnType = null)
    {
        if (!in_array(strtoupper($method), ['GET', 'PUT', 'POST', 'DELETE'])) {
            throw new Exception('Incorrect HTTP method provided.');
        }

        if (empty($this->accessToken)) {
            throw new Exception('No access token available.');
        }

        if ($this->accessToken->hasExpired()) {
            $this->accessToken = $this->refreshToken($this->accessToken);
        }

        try {
            $request = $this->getGraph()->createRequest(
                'GET',
                config('microsoft-graph.api.endpoint') . '/' . config('microsoft-graph.api.version') . $path
            )
            ->addHeaders(array("Content-Type" => "application/json"))
            ->setTimeout(1000);

            if (!is_null($returnType)) {
                $request->setReturnType($returnType);
            }

            $response = $request->execute();
        } catch (Exception $e) {
            switch ($e->getCode())
            {
                case 400:
                    throw new Exception('Bad request.', 400);
                    break;
                case 401: // Access token has expired.
                    $this->accessToken = $this->refreshToken($this->accessToken);
                    return $this->request($method, $path, $parameters, $returnType);
                    break;
                case 403:
                    throw new Exception('User does not have access.', 403);
                    break;
                case 404:
                    throw new Exception('Not found.', 404);
                    break;
                case 429:
                    $retryAfter = $e->getResponse()->getHeaders()['Retry-After'][0];
                    throw new Exception('Throttling (retry after: ' . $retryAfter . ').', 429);
                    break;
                case 503:
                    throw new Exception('Service unavailable.', 503);
                    break;
                default:
                    throw new Exception($e->getMessage());
                    break;
            }
        }

        return $response;
    }
}
