<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Session;

use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

interface ProductSessionHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $controllerEvent
     *
     * @return void
     */
    public function addProduct(FilterControllerEvent $controllerEvent): void;

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer|null
     */
    public function getProduct(string $sku): ?EnhancedEcommerceProductTransfer;

    /**
     * @param bool $remove
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer[]
     */
    public function getAllAddedProducts(bool $remove): array;

    /**
     * @return void
     */
    public function clearAddedProductsSession(): void;
}
