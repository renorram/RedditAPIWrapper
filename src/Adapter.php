<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

final class Adapter
{
    private Client $client;
    private Authentication $authentication;
    private AuthData $auth;
    private
    const BASE_URI = 'https://oauth.reddit.com';

    public function __construct(AppSettings $appSettings)
    {
        $this->authentication = new Authentication($appSettings, new CacheAdapter());
        $this->auth = $this->authentication->authenticate();
        $this->client = new Client(['base_uri' => self::BASE_URI]);
    }

    public function getMe(): ResponseInterface
    {
        return $this->client->request(
            'GET',
            '/api/v1/me',
            [
                RequestOptions::HEADERS => [
                    'User-Agent'    => $this->authentication->getAppSettings()->getAppName(),
                    'Authorization' => 'bearer ' . $this->getToken()
                ],
            ]
        );
    }

    private function getToken(): string
    {
        if (!$this->auth->isExpired()) {
            $this->auth = $this->authentication->authenticate();
        }

        return $this->auth->getAccessToken();
    }
}