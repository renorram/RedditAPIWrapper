<?php

namespace App;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use App\Factory\ClientFactory;
use App\Factory\CacheAdapterFactory;

final class Adapter
{
    private Client $client;
    const BASE_URI = 'https://oauth.reddit.com';

    public function __construct(AppSettings $appSettings)
    {
        $this->client = ClientFactory::createClientWithAuth(
            Authentication::create($appSettings, CacheAdapterFactory::createFSWithNamespace()),
            ['base_uri' => self::BASE_URI]
        );
    }

    public function getMe(): ResponseInterface
    {
        return $this->client->get('/api/v1/me');
    }

    public function getSubredditTopList(string $subReddit): ResponseInterface
    {
        return $this->client->get(\sprintf('/r/%s/top', $subReddit));
    }

    public function getSubredditBestList(string $subReddit): ResponseInterface
    {
        return $this->client->get(\sprintf('/r/%s/best', $subReddit));
    }
}