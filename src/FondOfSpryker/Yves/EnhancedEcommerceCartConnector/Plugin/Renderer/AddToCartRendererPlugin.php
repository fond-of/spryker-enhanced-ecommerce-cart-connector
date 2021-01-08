<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Plugin\Renderer;

use FondOfSpryker\Shared\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderPluginInterface;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRenderePluginInterface;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;

/**
 * @method \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorFactory getFactory()
 * @method \FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig getConfig()()
 */
class AddToCartRendererPlugin extends AbstractPlugin implements EnhancedEcommerceRenderePluginInterface
{
    /**
     * @param string $pageType
     * @param array $twigVariableBag
     *
     * @return bool
     */
    public function isApplicable(string $pageType, array $twigVariableBag = []): bool
    {
        return in_array($pageType, $this->getConfig()->getValidPageTypes())
            && $twigVariableBag[ModuleConstants::PARAM_PRODUCT] instanceof ProductViewTransfer;
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
        return $this->getFactory()
            ->createAddToCartRenderer()
            ->expand($twig, $page, $twigVariableBag);
    }
}
