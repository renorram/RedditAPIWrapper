<?php

namespace App\Tests;

use App\Adapter;
use App\AppSettings;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class AdapterTest extends TestCase
{
    private Adapter $adapter;
    protected function setUp(): void
    {
        parent::setUp();
        /** @todo get settings from env files */
        $appSettings = AppSettings::createFromArray(
            [
                'appName'      => "",
                'username'     => '',
                'password'     => '',
                'clientId'     => '',
                'clientSecret' => ''
            ]
        );

        $this->adapter = new Adapter($appSettings);
    }

    public function testItShouldBringMeInfo()
    {
        $response = $this->adapter->getMe();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testItShouldBringTopSubrreditPosts()
    {
        $response = $this->adapter->getSubredditTopList('funny');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testItShouldBringBestSubrreditPosts()
    {
        $response = $this->adapter->getSubredditBestList('funny');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        var_dump(json_decode($response->getBody(), true));
    }
}