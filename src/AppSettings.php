<?php

namespace App;

use InvalidArgumentException;
use ReflectionClass;

final class AppSettings
{
    private string $appName;
    private string $username;
    private string $password;
    private string $clientId;
    private string $clientSecret;

    /**
     * @return string
     */
    public function getAppName(): string
    {
        return $this->appName;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function __construct(
        string $appName,
        string $username,
        string $password,
        string $clientId,
        string $clientSecret
    ) {
        $this->appName = $appName;
        $this->username = $username;
        $this->password = $password;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public static function createFromArray(array $options): self
    {
        $instance = new ReflectionClass(self::class);
        $args = [];

        foreach ($instance->getProperties() as $property) {
            $propertyName = $property->getName();
            if (!isset($options[$propertyName])) {
                throw new InvalidArgumentException(
                    sprintf('Missing %s property in options array.', $property->getName())
                );
            }

            if (!is_string($options[$propertyName]) && $options[$propertyName] === "") {
                throw new InvalidArgumentException(
                    sprintf('property %s in invalid, must be a non empty string.', $property->getName())
                );
            }

            $args[$propertyName] = $options[$propertyName];
        }

        return $instance->newInstanceArgs($args);
    }
}