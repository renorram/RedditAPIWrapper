<?php

namespace App\Tests;

use App\AuthData;
use DateInterval;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;

class AuthDataTest extends TestCase
{
    /**
     * @param string $payload
     * @throws Exception
     * @dataProvider dataProvider
     */
    public function testItShouldGenerateAuthDataValid(string $payload)
    {
        $data = json_decode($payload, true);
        $authData = AuthData::createFromArray($data);
        $expireDate = new DateTime();
        $expireDate->add(new DateInterval('PT' . $data['expires_in'] . 'S'));

        $this->assertEquals($data['access_token'], $authData->getAccessToken());
        $this->assertEquals($data['token_type'], $authData->getTokenType());
        $this->assertSame($expireDate->format($expireDate::ATOM), $authData->getExpiresIn()->format($expireDate::ATOM));
    }

    /**
     * @param string $payload
     * @dataProvider dataProvider
     */
    public function testItIsExpired(string $payload)
    {
        $data = json_decode($payload, true);
        $data['expires_in'] = DateTime::createFromFormat(DateTime::ATOM, '2020-01-10T10:10:10+00');
        $authData = AuthData::createFromArray($data);

        $this->assertTrue($authData->isExpired());
    }

    public function dataProvider()
    {
        return [
            [
                "{
    \"access_token\": \"J1qK1c18UUGJFAzz9xnH56584l4\", 
    \"expires_in\": 3600, 
    \"scope\": \"*\", 
    \"token_type\": \"bearer\"
}"
            ]
        ];
    }
}