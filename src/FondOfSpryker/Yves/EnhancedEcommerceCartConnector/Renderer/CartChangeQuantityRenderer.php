<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Renderer;

use FondOfSpryker\Shared\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Converter\IntegerToDecimalConverterInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface;
use Generated\Shared\Transfer\EnhancedEcommerceAddEventTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Twig\Environment;

class CartChangeQuantityRenderer implements EnhancedEcommerceRendererInterface
{
    public const UNTRANSLATED_KEY = '_';

    public const EVENT_ADD = 'add';
    public const EVENT_REMOVE = 'remove';

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig
     */
    protected $ecommerceCartConnectorConfig;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Converter\IntegerToDecimalConverterInterface
     */
    protected $integerToDecimalConverter;

    /**
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig $ecommerceCartConnectorConfig
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency\EnhancedEcommerceCartConnectorToCartClientInterface $cartClient
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Converter\IntegerToDecimalConverterInterface $integerToDecimalConverter
     */
    public function __construct(
        EnhancedEcommerceCartConnectorConfig $ecommerceCartConnectorConfig,
        EnhancedEcommerceCartConnectorToCartClientInterface $cartClient,
        IntegerToDecimalConverterInterface $integerToDecimalConverter
    ) {
        $this->ecommerceCartConnectorConfig = $ecommerceCartConnectorConfig;
        $this->cartClient = $cartClient;
        $this->integerToDecimalConverter = $integerToDecimalConverter;
    }

    /**
     * @param \Twig\Environment $twig
     * @param string $page
     * @param array $twigVariableBag
     *
     * @return string
     */
    public function expand(Environment $twig, string $page, array $twigVariableBag): string
    {
        return $twig->render($this->getTemplate(), [
            'cartItem' => $this->getCartItems(),
            'addToCartLayer' => $this->createEnhancedEcommerceAddToCart()->toArray(true, true),
            'removeFromCartLayer' => $this->createEnhancedEcommerceRemoveFromCart()->toArray(true, true),
        ]);
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@EnhancedEcommerceCartConnector/partials/cart-change-quantity.js.twig';
    }

    /**
     * @return \Generated\Shared\Transfer\EnhancedEcommerceTransfer
     */
    protected function createEnhancedEcommerceAddToCart(): EnhancedEcommerceTransfer
    {
        $enhancedEcommerce = (new EnhancedEcommerceTransfer())
            ->setEvent(ModuleConstants::EVENT)
            ->setEventCategory(ModuleConstants::EVENT_CATEGORY)
            ->setEventAction(ModuleConstants::EVENT_ACTION_ADD_TO_CART)
            ->setEcommerce([static::EVENT_ADD => new EnhancedEcommerceAddEventTransfer()]);

        return $enhancedEcommerce;
    }

    /**
     * @return \Generated\Shared\Transfer\EnhancedEcommerceTransfer
     */
    protected function createEnhancedEcommerceRemoveFromCart(): EnhancedEcommerceTransfer
    {
        $enhancedEcommerce = (new EnhancedEcommerceTransfer())
            ->setEvent(ModuleConstants::EVENT)
            ->setEventCategory(ModuleConstants::EVENT_CATEGORY)
            ->setEventAction(ModuleConstants::EVENT_ACTION_REMOVE_FROM_CART)
            ->setEcommerce([static::EVENT_REMOVE => new EnhancedEcommerceAddEventTransfer()]);

        return $enhancedEcommerce;
    }

    /**
     * @return array
     */
    protected function getCartItems(): array
    {
        $cartItems = [];

        foreach ($this->cartClient->getQuote()->getItems() as $itemTransfer) {
            $cartItems[$itemTransfer->getSku()] = $this->removeEmptyArrayIndex(
                $this->createEnhancedEcommerceProductTransfer($itemTransfer)->toArray(true, true)
            );
        }

        return $cartItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer
     */
    protected function createEnhancedEcommerceProductTransfer(ItemTransfer $itemTransfer): EnhancedEcommerceProductTransfer
    {
        $enhancedEcommerceProductTransfer = (new EnhancedEcommerceProductTransfer())
            ->setId($itemTransfer->getSku())
            ->setBrand($this->getProductBrand($itemTransfer))
            ->setName($this->getProductName($itemTransfer))
            ->setVariant($this->getProductAttrStyle($itemTransfer))
            ->setDimension10($this->getSize($itemTransfer))
            ->setPrice('' . $this->integerToDecimalConverter->convert($itemTransfer->getUnitPrice()) . '')
            ->setQuantity($itemTransfer->getQuantity());

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

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED];
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

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_BRAND])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_BRAND];
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
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
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

            if (!$value && !in_array($key, $this->ecommerceCartConnectorConfig->getArrayIndexWhitelist())) {
                unset($haystack[$key]);
            }
        }

        return $haystack;
    }
}
