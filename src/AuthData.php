<?php

namespace App;

use DateInterval;
use DateTime;
use InvalidArgumentException;
use ReflectionClass;

final class AuthData
{
    private string $accessToken;
    private DateTime $expiresIn;
    private string $tokenType;

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return DateTime
     */
    public function getExpiresIn(): DateTime
    {
        return $this->expiresIn;
    }

    /**
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function __construct(
        string $accessToken,
        DateTime $expiresIn,
        string $tokenType
    ) {
        $this->accessToken = $accessToken;
        $this->expiresIn = $expiresIn;
        $this->tokenType = $tokenType;
    }

    public static function createFromArray(array $options): self
    {
        $instance = new ReflectionClass(self::class);
        $args = [];

        foreach ($instance->getProperties() as $property) {
            $propertyName = Util::camelCaseToSnake($property->getName());
            if (!isset($options[$propertyName])) {
                throw new InvalidArgumentException(
                    sprintf('Missing %s property in options array.', $property->getName())
                );
            }

            $args[$property->getName()] = $options[$propertyName];
        }

        if (is_numeric($args['expiresIn'])) {
            $expiresIn = new DateTime();
            $expiresIn->add(new DateInterval('PT' . $args['expiresIn'] . 'S'));
            $args['expiresIn'] = $expiresIn;
        }

        return $instance->newInstanceArgs($args);
    }

    public function isExpired(): bool
    {
        return $this->expiresIn < (new DateTime());
    }
}