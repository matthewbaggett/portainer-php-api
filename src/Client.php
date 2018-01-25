<?php

namespace Mangati\Portainer;

use Mangati\Api\Client as BaseClient;
use Mangati\Api\Path;

/**
 * Portainer Client
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class Client
{
    private $client;

    public function __construct(string $endpoint)
    {
        $this->client = new BaseClient($endpoint);
    }

    /**
     * Auth alias
     * @param string $user
     * @param string $pass
     * @throws Exception
     */
    public function login(string $user, string $pass)
    {
        return $this->auth($user, $pass);
    }

    /**
     * Authenticate against Portainer HTTP API
     * @param string $user
     * @param string $pass
     * @throws Exception
     */
    public function auth(string $user, string $pass)
    {
        $data = [
            'Username' => $user,
            'Password' => $pass,
        ];

        $json = $this->client->request('POST', 'auth', $data);
        $this->client->session()->headers[] = 'Authorization: Bearer ' . $json['jwt'];
    }

    /**
     * Docker registries API
     * @return Path
     */
    public function registries(): Path
    {
        $path = $this->client->createPath('registries');

        return $path;
    }

    /**
     * Docker environments API
     * @return Path
     */
    public function endpoints(): Path
    {
        $path = $this->client->createPath('endpoints');

        return $path;
    }

    /**
     * Docker stacks API
     * @param int $endpointId
     * @return Path
     */
    public function stacks(int $endpointId): Path
    {
        $path = $this->client->createPath("endpoints/{$endpointId}/stacks");

        return $path;
    }

    /**
     * Users API
     * @return Path
     */
    public function users(): Path
    {
        $path = $this->client->createPath('users');

        return $path;
    }

    /**
     * User memberships API
     * @param int $userId
     * @return Path
     */
    public function userMemberships(int $userId): Path
    {
        $path = $this->client->createPath("users/{$userId}/memberships");

        return $path;
    }

    /**
     * Teams API
     * @return Path
     */
    public function teams(): Path
    {
        $path = $this->client->createPath('teams');

        return $path;
    }

    /**
     * Team memberships API
     * @param int $userId
     * @return Path
     */
    public function teamMemberships(int $teamId): Path
    {
        $path = $this->client->createPath("teams/{$teamId}/memberships");

        return $path;
    }
}