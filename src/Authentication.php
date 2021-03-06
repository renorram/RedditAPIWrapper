<?php

namespace App;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;

final class Authentication
{
    private AppSettings $appSettings;
    private CacheInterface $cacheAdapter;

    public function __construct(AppSettings $appSettings, CacheInterface $cacheAdapter)
    {
        $this->appSettings = $appSettings;
        $this->cacheAdapter = $cacheAdapter;
    }

    public static function create(AppSettings $appSettings, CacheInterface $cacheAdapter): self
    {
        return new self($appSettings, $cacheAdapter);
    }

    public function getAppSettings(): AppSettings
    {
        return $this->appSettings;
    }

    /**
     * @return AuthData
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function authenticate(): AuthData
    {
        return $this->cacheAdapter->get(
            'app_data',
            function (ItemInterface $item) {
                $client = new Client(['base_uri' => 'https://www.reddit.com']);

                $response = $client->request(
                    'POST',
                    '/api/v1/access_token',
                    [
                        RequestOptions::AUTH        => [
                            $this->appSettings->getClientId(),
                            $this->appSettings->getClientSecret()
                        ],
                        RequestOptions::FORM_PARAMS => [
                            'username'   => $this->appSettings->getUsername(),
                            'password'   => $this->appSettings->getPassword(),
                            'grant_type' => 'password'
                        ],
                        RequestOptions::HEADERS     => [
                            'User-Agent' => $this->appSettings->getAppName(),
                        ]
                    ]
                );

                if ($response->getStatusCode() !== 200) {
                    throw new Exception(
                        sprintf("Response did not returned 200 it returned %d.", $response->getStatusCode())
                    );
                }

                $auth = AuthData::createFromArray(json_decode($response->getBody(), true));
                $item->expiresAt($auth->getExpiresIn());

                return $auth;
            }
        );
    }
}
