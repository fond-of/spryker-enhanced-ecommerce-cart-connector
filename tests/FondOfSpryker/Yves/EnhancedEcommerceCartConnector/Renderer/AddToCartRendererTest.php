<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Renderer;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Twig\Environment;

class AddToCartRendererTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPluginMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig
     */
    protected $configMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Twig\Environment
     */
    protected $twigMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\EnhancedEcommerceTransfer
     */
    protected $enhancedEcommerceTransferMock;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Renderer\AddToCartRenderer
     */
    protected $renderer;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->moneyPluginMock = $this->getMockBuilder(MoneyPluginInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock = $this->getMockBuilder(EnhancedEcommerceCartConnectorConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->twigMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->enhancedEcommerceTransferMock = $this->getMockBuilder(EnhancedEcommerceTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->renderer = new AddToCartRenderer($this->moneyPluginMock, $this->configMock);
    }

    /**
     * @return void
     */
    public function testExpand(): void
    {
        $twigVariableBag = include codecept_data_dir('twigVariableBag.php');

        $this->twigMock->expects($this->atLeastOnce())
            ->method('render')
            ->willReturn('string');

        $this->renderer->expand($this->twigMock, 'page', $twigVariableBag);
    }
}
