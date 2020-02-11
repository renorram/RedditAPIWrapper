<?php

namespace App\Factory;

use Psr\Http\Message\RequestInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use App\AuthData;
use App\Authentication;
use App\AppSettings;

final class ClientFactory
{
    public static function createClientWithAuth(Authentication $authentication, array $extraOptions = []): Client
    {
        $stack = HandlerStack::create();

        $auth = $authentication->authenticate();
        $appSettings = $authentication->getAppSettings();

        $stack->push(Middleware::mapRequest(function (RequestInterface $request) use ($appSettings) {
            return $request->withHeader('User-Agent', $appSettings->getAppName());
        }), 'add_user_agent');

        $stack->push(Middleware::mapRequest(function (RequestInterface $request) use ($auth) {
            return $request->withHeader('Authorization', $auth->getTokenType() . ' ' . $auth->getAccessToken());
        }), 'add_auth');

        return new Client(array_merge(['handler' => $stack], $extraOptions));
    }
}