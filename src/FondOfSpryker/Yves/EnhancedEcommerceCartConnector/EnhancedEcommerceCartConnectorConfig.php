<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector;

use FondOfSpryker\Shared\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class EnhancedEcommerceCartConnectorConfig extends AbstractBundleConfig
{
    /**
     * Returns list with array-keys which should not deleted even if they are empty
     *
     * @return array
     */
    public function getArrayIndexWhitelist(): array
    {
        return $this->get(EnhancedEcommerceCartConnectorConstants::CONFIG_DONT_UNSET_ARRAY_INDEX, [
            'action_field',
        ]);
    }

    /**
     * @return array
     */
    public function getValidPageTypes(): array
    {
        return $this->get(EnhancedEcommerceCartConnectorConstants::ADD_TO_CART_PLUGIN_VALID_PAGE_TYPES, [
            'productDetail',
        ]);
    }

    /**
     * @return string
     */
    public function getAddToCardFormId(): string
    {
        return $this->get(EnhancedEcommerceCartConnectorConstants::ADD_TO_CART_FORM_ID, '');
    }
}
