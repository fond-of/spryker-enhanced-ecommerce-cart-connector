<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Renderer;

use FondOfSpryker\Shared\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Converter\IntegerToDecimalConverterInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface;
use Generated\Shared\Transfer\EnhancedEcommerceAddEventTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Twig\Environment;

class AddToCartRenderer implements EnhancedEcommerceRendererInterface
{
    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig
     */
    protected $config;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Converter\IntegerToDecimalConverterInterface
     */
    protected $integerToDecimalConverter;

    /**
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Converter\IntegerToDecimalConverterInterface $integerToDecimalConverter
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig $config
     */
    public function __construct(
        IntegerToDecimalConverterInterface $integerToDecimalConverter,
        EnhancedEcommerceCartConnectorConfig $config
    ) {
        $this->config = $config;
        $this->integerToDecimalConverter = $integerToDecimalConverter;
    }

    /**
     * @param \Twig\Environment $twig
     * @param string $page
     * @param array $twigVariableBag
     *
     * @return string
     */
    public function render(Environment $twig, string $page, array $twigVariableBag): string
    {
        return $twig->render($this->getTemplate(), [
            'addToCartFormId' => $this->config->getAddToCardFormId(),
            'data' => $this->createEnhancedEcommerce($twigVariableBag)->toArray(true, true)
        ]);
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@EnhancedEcommerceCartConnector/partials/product-add-to-cart.js.twig';
    }

    /**
     * @param array $twigVariableBag
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceTransfer
     */
    protected function createEnhancedEcommerce(array $twigVariableBag): EnhancedEcommerceTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer */
        $productViewTransfer = $twigVariableBag[ModuleConstants::PARAM_PRODUCT];

        $enhancedEcommerce = (new EnhancedEcommerceTransfer())
            ->setEvent(ModuleConstants::EVENT)
            ->setEventCategory(ModuleConstants::EVENT_CATEGORY)
            ->setEventAction(ModuleConstants::EVENT_ACTION_ADD_TO_CART)
            ->setEventLabel($productViewTransfer->getSku())
            ->setEcommerce([
                'add' => (new EnhancedEcommerceAddEventTransfer())
                    ->addProduct($this->createEnhancedEcommerceProduct($productViewTransfer))
                    ->toArray(true, true),
            ]);

        return $enhancedEcommerce;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array
     */
    protected function createEnhancedEcommerceProduct(
        ProductViewTransfer $productViewTransfer
    ): array {
        return (new EnhancedEcommerceProductTransfer())
            ->setId($productViewTransfer->getSku())
            ->setName($this->getProductName($productViewTransfer))
            ->setVariant($this->getProductAttrStyle($productViewTransfer))
            ->setBrand($this->getProductBrand($productViewTransfer))
            ->setDimension10($this->getProductSize($productViewTransfer))
            ->setQuantity(1)
            ->setPrice('' . $this->integerToDecimalConverter->convert($productViewTransfer->getPrice()) . '')
            ->toArray(true, true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return string
     */
    protected function getProductName(ProductViewTransfer $productViewTransfer): string
    {
        $productAttributes = $productViewTransfer->getAttributes();

        if (count($productAttributes) === 0) {
            return $productViewTransfer->getName();
        }

        if (isset($productAttributes[ModuleConstants::PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED])) {
            return $productAttributes[ModuleConstants::PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED];
        }

        if (isset($productAttributes[ModuleConstants::PARAM_PRODUCT_ATTR_MODEL])) {
            return $productAttributes[ModuleConstants::PARAM_PRODUCT_ATTR_MODEL];
        }

        return $productViewTransfer->getName();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return string
     */
    protected function getProductAttrStyle(ProductViewTransfer $productViewTransfer): string
    {
        $productAttributes = $productViewTransfer->getAttributes();

        if (count($productAttributes) === 0) {
            return '';
        }

        if (isset($productAttributes[ModuleConstants::PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED])) {
            return $productAttributes[ModuleConstants::PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED];
        }

        if (isset($productAttributes[ModuleConstants::PARAM_PRODUCT_ATTR_STYLE])) {
            return $productAttributes[ModuleConstants::PARAM_PRODUCT_ATTR_STYLE];
        }

        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return string
     */
    protected function getProductBrand(ProductViewTransfer $productViewTransfer): string
    {
        $productAttributes = $productViewTransfer->getAttributes();

        if (count($productAttributes) === 0) {
            return '';
        }

        if (isset($productAttributes[ModuleConstants::PARAM_PRODUCT_ATTR_BRAND])) {
            return $productAttributes[ModuleConstants::PARAM_PRODUCT_ATTR_BRAND];
        }

        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return string
     */
    protected function getProductSize(ProductViewTransfer $productViewTransfer): string
    {
        $productAttributes = $productViewTransfer->getAttributes();

        if (count($productAttributes) === 0) {
            return '';
        }

        if (isset($productAttributes[ModuleConstants::PARAM_PRODUCT_ATTR_SIZE_UNTRANSLATED])) {
            return $productAttributes[ModuleConstants::PARAM_PRODUCT_ATTR_SIZE_UNTRANSLATED];
        }

        if (isset($productAttributes[ModuleConstants::PARAM_PRODUCT_ATTR_SIZE])) {
            return $productAttributes[ModuleConstants::PARAM_PRODUCT_ATTR_SIZE];
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

            if (!$value) {
                unset($haystack[$key]);
            }
        }

        return $haystack;
    }
}
