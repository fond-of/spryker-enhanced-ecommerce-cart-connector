<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Session;

use FondOfSpryker\Shared\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToSessionClientInterface;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ProductSessionHandler implements ProductSessionHandlerInterface
{
    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToSessionClientInterface
     */
    protected $sessionClient;

    /**
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToSessionClientInterface $sessionClient
     */
    public function __construct(
        EnhancedEcommerceCartConnectorToSessionClientInterface $sessionClient
    ) {
        $this->sessionClient = $sessionClient;

        if ($this->sessionClient->get(ModuleConstants::EEC_ADDED_PRODUCTS) === null) {
            $this->sessionClient->set(ModuleConstants::EEC_ADDED_PRODUCTS, []);
        }
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $controllerEvent
     *
     * @return void
     */
    public function addProduct(FilterControllerEvent $controllerEvent): void
    {
        if (!$controllerEvent->getRequest()->get(ModuleConstants::REQUEST_SKU)) {
            return;
        }

        $sku = $controllerEvent->getRequest()->get(ModuleConstants::REQUEST_SKU);
        $quantity = $controllerEvent->getRequest()->get(ModuleConstants::REQUEST_QUANTITY) ?: 1;

        if ($this->getProduct($sku) === null) {
            $addedProducts = $this->sessionClient->get(ModuleConstants::EEC_ADDED_PRODUCTS);
            $addedProducts[$sku] = (new EnhancedEcommerceProductTransfer())
                ->setId($sku)
                ->setQuantity($quantity);

            $this->sessionClient->set(ModuleConstants::EEC_ADDED_PRODUCTS, $addedProducts);

            return;
        }
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer|null
     */
    public function getProduct(string $sku): ?EnhancedEcommerceProductTransfer
    {
        $addedProducts = $this->sessionClient->get(ModuleConstants::EEC_ADDED_PRODUCTS);

        if (array_key_exists($sku, $addedProducts)) {
            return $addedProducts[$sku];
        }

        return null;
    }

    /**
     * @param bool $remove
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer[]
     */
    public function getAllAddedProducts(bool $remove = false): array
    {
        $products = $this->sessionClient->get(ModuleConstants::EEC_ADDED_PRODUCTS);

        if ($remove === true) {
            $this->clearAddedProductsSession();
        }

        return $products;
    }

    /**
     * @return void
     */
    public function clearAddedProductsSession(): void
    {
        $this->sessionClient->set(ModuleConstants::EEC_ADDED_PRODUCTS, []);
    }
}
