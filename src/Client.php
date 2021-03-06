<?php

namespace Mangati\Portainer;

use Mangati\Api\Client as BaseClient;
use Mangati\Api\Path;

/**
 * Portainer Client
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class Client extends BaseClient
{
    private $auth;
    private $isAuthed = false;

    public function __construct(string $endpoint)
    {
        parent::__construct($endpoint);
    }

    public function request(string $method, string $path, array $data = [], array $headers = []): array
    {
        if(!$this->isAuthed) {
            $this->doAuth();
        }
        return parent::request($method, $path, $data, $headers);
    }

    /**
     * Authenticate against Portainer HTTP API
     * @param string $user
     * @param string $pass
     */
    public function setAuth(string $user, string $pass)
    {
        $this->auth = [
            'Username' => $user,
            'Password' => $pass,
        ];
    }

    public function doAuth()
    {
        $json = parent::request('POST', 'auth', $this->auth);
        $this->session()->headers[] = 'Authorization: Bearer ' . $json['jwt'];
        $this->isAuthed = true;
    }

    /**
     * Docker registries API
     * @return Path
     */
    public function registries(): Path
    {
        $path = $this->createPath('registries');

        return $path;
    }

    /**
     * Docker environments API
     * @return Path
     */
    public function endpoints(): Path
    {
        $path = $this->createPath('endpoints');

        return $path;
    }

    /**
     * Docker API
     * @param int $endpointId
     * @return Path
     */
    public function dockerInfo(int $endpointId): array
    {
        $info = $this->request('GET', "endpoints/{$endpointId}/docker/info", [], $this->session()->headers);

        return $info;
    }

    /**
     * Docker stacks API
     * @param int $endpointId
     * @return Path
     */
    public function stacks(int $endpointId): Path
    {
        $path = $this->createPath("endpoints/{$endpointId}/stacks");

        return $path;
    }

    /**
     * Users API
     * @return Path
     */
    public function users(): Path
    {
        $path = $this->createPath('users');

        return $path;
    }

    /**
     * User memberships API
     * @param int $userId
     * @return Path
     */
    public function userMemberships(int $userId): Path
    {
        $path = $this->createPath("users/{$userId}/memberships");

        return $path;
    }

    /**
     * Teams API
     * @return Path
     */
    public function teams(): Path
    {
        $path = $this->createPath('teams');

        return $path;
    }

    /**
     * Team memberships API
     * @param int $teamId
     * @return Path
     */
    public function teamMemberships(int $teamId): Path
    {
        $path = $this->createPath("teams/{$teamId}/memberships");

        return $path;
    }

    /**
     * Networks API
     * @param int $endpointId
     * @return Path
     */
    public function networks(int $endpointId) : Path
    {
        $path = $this->createPath("endpoints/{$endpointId}/docker/networks");

        return $path;
    }

}

