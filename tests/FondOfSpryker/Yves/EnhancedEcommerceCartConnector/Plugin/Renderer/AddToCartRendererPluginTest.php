<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Plugin\Renderer;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorFactory;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface;
use Generated\Shared\Transfer\ProductViewTransfer;
use Twig\Environment;

class AddToCartRendererPluginTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorFactory
     */
    protected $factoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig
     */
    protected $configMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Twig\Environment
     */
    protected $twigMock;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Plugin\Renderer\AddToCartRendererPlugin
     */
    protected $plugin;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ProductViewTransfer
     */
    protected $productViewTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface
     */
    protected $addToCartRendererMock;

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

        $this->productViewTransferMock = $this->getMockBuilder(ProductViewTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->addToCartRendererMock = $this->getMockBuilder(EnhancedEcommerceRendererInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->plugin = new AddToCartRendererPlugin();
        $this->plugin->setFactory($this->factoryMock);
        $this->plugin->setConfig($this->configMock);
    }

    /**
     * @return void
     */
    public function testIsApplicable(): void
    {
        $this->configMock->expects($this->atLeastOnce())
            ->method('getValidPageTypes')
            ->willReturn(['page']);

        $this->plugin->isApplicable('page', ['product' => $this->productViewTransferMock]);
    }

    /**
     * @return void
     */
    public function testRender(): void
    {
        $this->factoryMock->expects($this->atLeastOnce())
            ->method('createAddToCartRenderer')
            ->willReturn($this->addToCartRendererMock);

        $this->addToCartRendererMock->expects($this->atLeastOnce())
            ->method('render');

        $this->plugin->render($this->twigMock, 'page', ['product' => $this->productViewTransferMock]);
    }
}
