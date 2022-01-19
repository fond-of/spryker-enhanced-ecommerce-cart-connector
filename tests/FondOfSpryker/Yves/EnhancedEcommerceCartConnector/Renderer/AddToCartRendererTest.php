<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Renderer;

use Codeception\Test\Unit;
use FondOfSpryker\Shared\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConstants;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Converter\IntegerToDecimalConverter;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Twig\Environment;

class AddToCartRendererTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Converter\IntegerToDecimalConverterInterface
     */
    protected $integerToDecimalConverterMock;

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
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface
     */
    protected $productModelMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ProductViewTransfer
     */
    protected $productViewTransferMock;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Renderer\AddToCartRenderer
     */
    protected $renderer;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->integerToDecimalConverterMock = $this->getMockBuilder(IntegerToDecimalConverter::class)
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

        $this->enhancedEcommerceTransferMock = $this->getMockBuilder(EnhancedEcommerceTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->renderer = new AddToCartRenderer(
            $this->integerToDecimalConverterMock,
            $this->configMock
        );
    }

    /**
     * @return void
     */
    public function testRender(): void
    {
        $this->twigMock->expects($this->atLeastOnce())
            ->method('render')
            ->willReturn('string');

        $this->configMock->expects(static::atLeastOnce())
            ->method('getAddToCardFormId')
            ->willReturn('form-id');

        $this->productViewTransferMock->expects(static::atLeastOnce())
            ->method('getAttributes')
            ->willReturn(['some' => 'attributes']);

        $this->productViewTransferMock->expects(static::atLeastOnce())
            ->method('getName')
            ->willReturn('product name');

        $this->renderer->render($this->twigMock, 'page', [EnhancedEcommerceCartConnectorConstants::PARAM_PRODUCT => $this->productViewTransferMock]);
    }
}
