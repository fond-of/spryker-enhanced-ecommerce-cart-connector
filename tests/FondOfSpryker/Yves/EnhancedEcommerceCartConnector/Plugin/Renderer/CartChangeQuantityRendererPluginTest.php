<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Plugin\Renderer;

use Codeception\Test\Unit;
use FondOfSpryker\Shared\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConstants;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorFactory;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface;
use Twig\Environment;

class CartChangeQuantityRendererPluginTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorFactory
     */
    protected $factoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Twig\Environment
     */
    protected $twigMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface
     */
    protected $cartChangeQuantityRendererMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig
     */
    protected $configMock;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Plugin\Renderer\CartChangeQuantityRendererPlugin
     */
    protected $plugin;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->factoryMock = $this->getMockBuilder(EnhancedEcommerceCartConnectorFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock = $this->getMockBuilder(EnhancedEcommerceCartConnectorConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->twigMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cartChangeQuantityRendererMock = $this->getMockBuilder(EnhancedEcommerceRendererInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->plugin = new CartChangeQuantityRendererPlugin();
        $this->plugin->setFactory($this->factoryMock);
    }

    /**
     * @return void
     */
    public function testIsApplicable(): void
    {
        $this->assertEquals(true, $this->plugin->isApplicable(EnhancedEcommerceCartConnectorConstants::PAGE_TYPE));
    }

    /**
     * @return void
     */
    public function testRender(): void
    {
        $this->factoryMock->expects($this->atLeastOnce())
            ->method('createCartChangeQuantityRenderer')
            ->willReturn($this->cartChangeQuantityRendererMock);

        $this->cartChangeQuantityRendererMock->expects($this->atLeastOnce())
            ->method('render');

        $this->plugin->render($this->twigMock, 'page', []);
    }
}
