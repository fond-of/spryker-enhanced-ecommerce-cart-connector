<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector;

use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToCartClientBridge;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Money\Plugin\MoneyPlugin;

class EnhancedEcommerceCartConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PRODUCT_RESOURCE_ALIAS_STORAGE_CIENT = 'PRODUCT_RESOURCE_ALIAS_STORAGE_CIENT';
    public const STORE = 'STORE';
    public const CART_CLIENT = 'CART_CLIENT';
    public const PLUGIN_MONEY = 'PLUGIN_MONEY';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $this->addStore($container);
        $this->addCartClient($container);
        $this->addMoneyPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStore(Container $container): Container
    {
        $container->set(static::STORE, function (Container $container) {
            return Store::getInstance();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container->set(static::CART_CLIENT, function (Container $container) {
            return new EnhancedEcommerceCartConnectorToCartClientBridge(
                $container->getLocator()->cart()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMoneyPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_MONEY, static function () {
            return new MoneyPlugin();
        });

        return $container;
    }
}
