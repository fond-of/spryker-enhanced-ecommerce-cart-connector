<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\CartClientInterface;

class EnhancedEcommerceCartConnectorToCartClientBridgeTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Cart\CartClientInterface
     */
    protected $cartClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransferMock;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToCartClientBridge
     */
    protected $bridge;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->cartClientMock = $this->getMockBuilder(CartClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteTransferMock = $this->getMockBuilder(QuoteTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->bridge = new EnhancedEcommerceCartConnectorToCartClientBridge($this->cartClientMock);
    }

    /**
     * @return void
     */
    public function testGetQuote(): void
    {
        $this->cartClientMock->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->bridge->getQuote();
    }
}
