<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency;

use Spryker\Client\Session\SessionClientInterface;

class EnhancedEcommerceCartConnectorToSessionClientBridge implements EnhancedEcommerceCartConnectorToSessionClientInterface
{
    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    private $sessionClient;

    /**
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     */
    public function __construct(SessionClientInterface $sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * Checks if an attribute is defined.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->sessionClient->has($name);
    }

    /**
     * Returns an attribute.
     *
     * @param mixed $default The default value if not found
     *
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return $this->sessionClient->get($name, $default);
    }

    /**
     * Sets an attribute.
     *
     * @param mixed $value
     *
     * @return void
     */
    public function set(string $name, $value): void
    {
        $this->sessionClient->set($name, $value);
    }

    /**
     * Sets attributes.
     *
     * @return void
     */
    public function replace(array $attributes): void
    {
        $this->sessionClient->replace($attributes);
    }

    /**
     * Removes an attribute.
     *
     * @return mixed The removed value or null when it does not exist
     */
    public function remove(string $name)
    {
        $this->sessionClient->remove($name);
    }
}
