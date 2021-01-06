<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector;

use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToSessionClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Expander\DataLayerExpander;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Expander\DataLayerExpanderInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Session\ProductSessionHandler;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Session\ProductSessionHandlerInterface;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig getConfig()
 */
class EnhancedEcommerceCartConnectorFactory extends AbstractFactory
{
    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToSessionClientInterface
     */
    public function getSessionClient(): EnhancedEcommerceCartConnectorToSessionClientInterface
    {
        return $this->getProvidedDependency(EnhancedEcommerceCartConnectorDependencyProvider::SESSION_CLIENT);
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToCartClientInterface
     */
    public function getCartClient(): EnhancedEcommerceCartConnectorToCartClientInterface
    {
        return $this->getProvidedDependency(EnhancedEcommerceCartConnectorDependencyProvider::CART_CLIENT);
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    public function getMoneyPlugin(): MoneyPluginInterface
    {
        return $this->getProvidedDependency(EnhancedEcommerceCartConnectorDependencyProvider::PLUGIN_MONEY);
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Session\ProductSessionHandlerInterface
     */
    public function createProductSessionHandler(): ProductSessionHandlerInterface
    {
        return new ProductSessionHandler($this->getSessionClient());
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Expander\DataLayerExpanderInterface
     */
    public function createDataLayerExpander(): DataLayerExpanderInterface
    {
        return new DataLayerExpander(
            $this->createProductSessionHandler(),
            $this->getConfig(),
            $this->getCartClient(),
            $this->getMoneyPlugin()
        );
    }
}
