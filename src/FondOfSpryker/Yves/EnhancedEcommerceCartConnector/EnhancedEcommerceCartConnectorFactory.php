<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector;

use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Converter\IntegerToDecimalConverterInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Renderer\AddToCartRenderer;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Renderer\CartChangeQuantityRenderer;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface;
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
     * @return \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Converter\IntegerToDecimalConverterInterface
     */
    public function getIntegerToDecimalConverter(): IntegerToDecimalConverterInterface
    {
        return $this->getProvidedDependency(EnhancedEcommerceCartConnectorDependencyProvider::CONVERTER_INTERGER_TO_DECIMAL);
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface
     */
    public function createCartChangeQuantityRenderer(): EnhancedEcommerceRendererInterface
    {
        return new CartChangeQuantityRenderer(
            $this->getConfig(),
            $this->getCartClient(),
            $this->getIntegerToDecimalConverter(),
        );
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface
     */
    public function createAddToCartRenderer(): EnhancedEcommerceRendererInterface
    {
        return new AddToCartRenderer($this->getIntegerToDecimalConverter(), $this->getConfig());
    }
}
