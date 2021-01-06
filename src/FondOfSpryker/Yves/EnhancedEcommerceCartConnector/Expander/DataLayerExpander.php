<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Expander;

use FondOfSpryker\Shared\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Session\ProductSessionHandlerInterface;
use Generated\Shared\Transfer\EnhancedEcommerceAddEventTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class DataLayerExpander implements DataLayerExpanderInterface
{
    public const UNTRANSLATED_KEY = '_';

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Session\ProductSessionHandlerInterface
     */
    protected $productSessionHandler;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig
     */
    protected $ecommerceCartConnectorConfig;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Session\ProductSessionHandlerInterface $productSessionHandler
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig $ecommerceCartConnectorConfig
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToCartClientInterface $cartClient
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     */
    public function __construct(
        ProductSessionHandlerInterface $productSessionHandler,
        EnhancedEcommerceCartConnectorConfig $ecommerceCartConnectorConfig,
        EnhancedEcommerceCartConnectorToCartClientInterface $cartClient,
        MoneyPluginInterface $moneyPlugin
    ) {
        $this->productSessionHandler = $productSessionHandler;
        $this->ecommerceCartConnectorConfig = $ecommerceCartConnectorConfig;
        $this->cartClient = $cartClient;
        $this->moneyPlugin = $moneyPlugin;
    }

    /**
     * @param string $page
     * @param array $twigVariableBag
     * @param array $dataLayer
     *
     * @return array
     */
    public function expand(string $page, array $twigVariableBag, array $dataLayer): array
    {
        return $this->addEventAddProduct();
    }

    /**
     * @return array
     */
    protected function addEventAddProduct(): array
    {
        if (count($this->productSessionHandler->getAllAddedProducts()) > 0) {
            $enhancedEcommerceTransfer = (new EnhancedEcommerceTransfer())
                ->setEvent(ModuleConstants::EVENT)
                ->setEventCategory(ModuleConstants::EVENT_CATEGORY)
                ->setEventAction(ModuleConstants::EVENT_ACTION_ADD_TO_CART)
                ->setEventLabel(implode(',', $this->getSkuOfAddedProducts()))
                ->setEcommerce([
                    'add' => $this->getEventEcommerceAdd()->toArray(),
                ]);

            //$this->productSessionHandler->clearAddedProductsSession();

            return $this->removeEmptyArrayIndex($enhancedEcommerceTransfer->toArray());
        }

        return [];
    }

    /**
     * @return \Generated\Shared\Transfer\EnhancedEcommerceAddEventTransfer
     */
    protected function getEventEcommerceAdd(): EnhancedEcommerceAddEventTransfer
    {
        $enhancedEcommerceAddEventTransfer = (new EnhancedEcommerceAddEventTransfer())
            ->setActionField([]);

        foreach ($this->productSessionHandler->getAllAddedProducts() as $product) {
            if (!$product->getId()) {
                continue;
            }

            $itemTransfer = $this->getProductFromQuote($product->getId());

            if ($itemTransfer === null) {
                continue;
            }

            $enhancedEcommerceAddEventTransfer->addProduct($this->completeProduct($itemTransfer, $product));
        }

        return $enhancedEcommerceAddEventTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer $enhancedEcommerceProductTransfer
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer
     */
    protected function completeProduct(
        ItemTransfer $itemTransfer,
        EnhancedEcommerceProductTransfer $enhancedEcommerceProductTransfer
    ): EnhancedEcommerceProductTransfer {
        $enhancedEcommerceProductTransfer
            ->setId($itemTransfer->getSku())
            ->setBrand($this->getProductBrand($itemTransfer))
            ->setName($this->getProductName($itemTransfer))
            ->setVariant($this->getProductAttrStyle($itemTransfer))
            ->setDimension10($this->getSize($itemTransfer))
            ->setPrice($this->moneyPlugin->convertIntegerToDecimal($itemTransfer->getUnitPrice()));

        return $enhancedEcommerceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getProductName(ItemTransfer $itemTransfer): string
    {
        $productAttributes = $itemTransfer->getAbstractAttributes();

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAMETER_PRODUCT_ATTR_NAME_UNTRANSLATED])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAMETER_PRODUCT_ATTR_NAME_UNTRANSLATED];
        }

        return $itemTransfer->getName();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getProductBrand(ItemTransfer $itemTransfer): string
    {
        $productAttributes = $itemTransfer->getAbstractAttributes();

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAMETER_PRODUCT_ATTR_BRAND])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAMETER_PRODUCT_ATTR_BRAND];
        }

        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getSize(ItemTransfer $itemTransfer): string
    {
        $productAttributes = $itemTransfer->getAbstractAttributes();

        if (count($productAttributes) === 0) {
            return '';
        }

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_SIZE_UNTRANSLATED])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_SIZE_UNTRANSLATED];
        }

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_SIZE])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_SIZE];
        }

        return '';
    }

    /**
     * @param ProductViewTransfer $itemTransfer
     *
     * @return string
     */
    protected function getProductAttrStyle(ItemTransfer $itemTransfer): string
    {
        $productAttributes = $itemTransfer->getAbstractAttributes();

        if (count($productAttributes) === 0) {
            return '';
        }

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED];
        }

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE];
        }

        return '';
    }

    /**
     * @return array
     */
    protected function getSkuOfAddedProducts(): array
    {
        $skus = [];

        /** @var \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer $enhancedEcommerceProductTransfer */
        foreach ($this->productSessionHandler->getAllAddedProducts() as $enhancedEcommerceProductTransfer) {
            $skus[] = $enhancedEcommerceProductTransfer->getId();
        }

        return $skus;
    }

    /**
     * @param array $haystack
     *
     * @return array
     */
    protected function removeEmptyArrayIndex(array $haystack): array
    {
        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $haystack[$key] = $this->removeEmptyArrayIndex($haystack[$key]);
            }

            if (!$value && !in_array($key, $this->ecommerceCartConnectorConfig->getDontUnsetArrayIndex())) {
                unset($haystack[$key]);
            }
        }

        return $haystack;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function getProductFromQuote(string $sku): ?ItemTransfer
    {
        foreach ($this->cartClient->getQuote()->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() === $sku) {
                return $itemTransfer;
            }
        }

        return null;
    }
}
