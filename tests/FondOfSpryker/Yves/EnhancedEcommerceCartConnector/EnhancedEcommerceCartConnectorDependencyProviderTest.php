<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector;

use Codeception\Test\Unit;
use Spryker\Yves\Kernel\Container;

class EnhancedEcommerceCartConnectorDependencyProviderTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Kernel\Container
     */
    protected $containerMock;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorDependencyProvider
     */
    protected $dependencyProvider;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dependencyProvider = new EnhancedEcommerceCartConnectorDependencyProvider();
    }

    /**
     * @return void
     */
    public function testProvideDependencies(): void
    {
        $this->assertInstanceOf(
            Container::class,
            $this->dependencyProvider->provideDependencies(
                $this->containerMock,
            ),
        );
    }
}
