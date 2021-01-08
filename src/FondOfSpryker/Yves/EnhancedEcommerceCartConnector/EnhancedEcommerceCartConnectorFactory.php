<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector;

use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Renderer\AddToCartRenderer;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Renderer\CartChangeQuantityRenderer;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig getConfig()
 */
class EnhancedEcommerceCartConnectorFactory extends AbstractFactory
{
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
     * @return EnhancedEcommerceRendererInterface
     */
    public function createCartChangeQuantityRenderer(): EnhancedEcommerceRendererInterface
    {
        return new CartChangeQuantityRenderer(
            $this->getConfig(),
            $this->getCartClient(),
            $this->getMoneyPlugin()
        );
    }

    /**
     * @return EnhancedEcommerceRendererInterface
     */
    public function createAddToCartRenderer(): EnhancedEcommerceRendererInterface
    {
        return new AddToCartRenderer($this->getMoneyPlugin(), $this->getConfig());
    }
}
