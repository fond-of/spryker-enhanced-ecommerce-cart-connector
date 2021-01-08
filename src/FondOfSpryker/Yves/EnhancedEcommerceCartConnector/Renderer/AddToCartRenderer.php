<?php


namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Renderer;


use FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface;
use Generated\Shared\Transfer\EnhancedEcommerceAddEventTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Twig\Environment;
use FondOfSpryker\Shared\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConstants as ModuleConstants;

class AddToCartRenderer implements EnhancedEcommerceRendererInterface
{
    /**
     * @var MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @var EnhancedEcommerceCartConnectorConfig
     */
    protected $config;

    public function __construct(MoneyPluginInterface $moneyPlugin, EnhancedEcommerceCartConnectorConfig $config)
    {
        $this->moneyPlugin = $moneyPlugin;
        $this->config = $config;
    }

    /**
     * @param Environment $twig
     * @param string $page
     * @param array $twigVariableBag
     *
     * @return string
     */
    public function expand(Environment $twig, string $page, array $twigVariableBag): string
    {
        return $twig->render($this->getTemplate(), [
            'data' => $this->createEnhancedEcommerce($twigVariableBag)->toArray(),
        ]);
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@EnhancedEcommerceCartConnector/partials/product-add-to-cart.js.twig';
    }

    protected function createEnhancedEcommerce(array $twigVariableBag): EnhancedEcommerceTransfer
    {
        /** @var ProductViewTransfer $productViewTransfer */
        $productViewTransfer = $twigVariableBag[ModuleConstants::PARAM_PRODUCT];

        $enhancedEcommerce = (new EnhancedEcommerceTransfer())
            ->setEvent(ModuleConstants::EVENT)
            ->setEventCategory(ModuleConstants::EVENT_CATEGORY)
            ->setEventAction(ModuleConstants::EVENT_ACTION_ADD_TO_CART)
            ->setEventLabel($productViewTransfer->getSku())
            ->setEcommerce(['add' => (new EnhancedEcommerceAddEventTransfer())
                ->addProduct($this->createEnhancedEcommerceProduct($productViewTransfer))
            ])
        ;

        return $enhancedEcommerce;
    }

    /**
     * @param ProductViewTransfer $productViewTransfer
     *
     * @return EnhancedEcommerceProductTransfer
     */
    protected function createEnhancedEcommerceProduct(
        ProductViewTransfer $productViewTransfer
    ): EnhancedEcommerceProductTransfer
    {
        return (new EnhancedEcommerceProductTransfer())
            ->setId($productViewTransfer->getSku())
            ->setName($this->getProductName($productViewTransfer))
            ->setVariant($this->getProductAttrStyle($productViewTransfer))
            ->setBrand($this->getProductBrand($productViewTransfer))
            ->setDimension10($this->getProductSize($productViewTransfer))
            ->setQuantity(1)
            ->setPrice($this->moneyPlugin->convertIntegerToDecimal($productViewTransfer->getPrice()))
        ;
    }

    /**
     * @param array $product
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
     * @param ProductViewTransfer $productViewTransfer
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
     * @param ProductViewTransfer $productViewTransfer
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
     * @param ProductViewTransfer $productViewTransfer
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
}
