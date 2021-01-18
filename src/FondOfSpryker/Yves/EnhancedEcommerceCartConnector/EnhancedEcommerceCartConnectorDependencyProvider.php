<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector;

use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Converter\IntegerToDecimalConverter;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Converter\IntegerToDecimalConverterInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToCartClientBridge;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class EnhancedEcommerceCartConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CART_CLIENT = 'CART_CLIENT';
    public const CONVERTER_INTERGER_TO_DECIMAL = 'CONVERTER_INTERGER_TO_DECIMAL';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $this->addCartClient($container);
        $this->addIntegerToDecimalConverter($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container->set(static::CART_CLIENT, static function (Container $container) {
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
    protected function addIntegerToDecimalConverter(Container $container): Container
    {
        $self = $this;

        $container->set(static::CONVERTER_INTERGER_TO_DECIMAL, static function () use ($self) {
            return $self->getIntegerToDecimalConverter();
        });

        return $container;
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Converter\IntegerToDecimalConverterInterface
     */
    protected function getIntegerToDecimalConverter(): IntegerToDecimalConverterInterface
    {
        return new IntegerToDecimalConverter();
    }
}
