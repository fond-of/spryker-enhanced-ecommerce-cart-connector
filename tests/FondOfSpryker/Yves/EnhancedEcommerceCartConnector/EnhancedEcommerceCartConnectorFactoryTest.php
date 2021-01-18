<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Converter\IntegerToDecimalConverter;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToCartClientInterface;
use Spryker\Yves\Kernel\Container;

class EnhancedEcommerceCartConnectorFactoryTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Kernel\Container
     */
    protected $containerMock;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorFactory
     */
    protected $factory;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Kernel\Container
     */
    protected $cartClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Converter\IntegerToDecimalConverterInterface
     */
    protected $integerToDecimalConverterMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->integerToDecimalConverterMock = $this->getMockBuilder(IntegerToDecimalConverter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cartClientMock = $this->getMockBuilder(EnhancedEcommerceCartConnectorToCartClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->factory = new EnhancedEcommerceCartConnectorFactory();
        $this->factory->setContainer($this->containerMock);
    }

    /**
     * @return void
     */
    public function testGetCartClient(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->with(EnhancedEcommerceCartConnectorDependencyProvider::CART_CLIENT)
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->with(EnhancedEcommerceCartConnectorDependencyProvider::CART_CLIENT)
            ->willReturn($this->cartClientMock);

        $this->factory->getCartClient();
    }

    /**
     * @return void
     */
    public function testGetIntegerToDecimalConverter(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->with(EnhancedEcommerceCartConnectorDependencyProvider::CONVERTER_INTERGER_TO_DECIMAL)
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->with(EnhancedEcommerceCartConnectorDependencyProvider::CONVERTER_INTERGER_TO_DECIMAL)
            ->willReturn($this->integerToDecimalConverterMock);

        $this->factory->getIntegerToDecimalConverter();
    }
}
