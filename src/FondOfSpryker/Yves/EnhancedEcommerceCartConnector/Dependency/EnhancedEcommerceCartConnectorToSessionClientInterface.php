<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency;

interface EnhancedEcommerceCartConnectorToSessionClientInterface
{
    /**
     * Checks if an attribute is defined.
     *
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * Returns an attribute.
     *
     * @param mixed $default The default value if not found
     *
     * @return mixed
     */
    public function get(string $name, $default = null);

    /**
     * Sets an attribute.
     *
     * @param mixed $value
     *
     * @return void
     */
    public function set(string $name, $value): void;

    /**
     * Sets attributes.
     *
     * @return void;
     */
    public function replace(array $attributes): void;

    /**
     * Removes an attribute.
     *
     * @return mixed The removed value or null when it does not exist
     */
    public function remove(string $name);
}
