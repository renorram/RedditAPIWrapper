<?php

namespace App\Tests;

use App\Adapter;
use App\AppSettings;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class AdapterTest extends TestCase
{
    /**
     * @todo
     */
    public function testItShouldBringMeInfo()
    {
        $appSettings = AppSettings::createFromArray(
            [
                'appName'      => "",
                'username'     => '',
                'password'     => '',
                'clientId'     => '',
                'clientSecret' => ''
            ]
        );

        $adapter = new Adapter($appSettings);

        $response = $adapter->getMe();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}