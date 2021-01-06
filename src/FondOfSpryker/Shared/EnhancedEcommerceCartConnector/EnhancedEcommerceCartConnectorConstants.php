<?php

namespace FondOfSpryker\Shared\EnhancedEcommerceCartConnector;

interface EnhancedEcommerceCartConnectorConstants
{
    public const PAGE_TYPE = 'cart';

    public const EEC_ADDED_PRODUCTS = 'EEC_ADDED_PRODUCTS';

    public const REQUEST_SKU = 'sku';
    public const REQUEST_QUANTITY = 'quantity';

    public const EVENT = 'genericEvent';
    public const EVENT_CATEGORY = 'ecommerce';
    public const EVENT_ACTION_ADD_TO_CART = 'addToCart';

    public const CONFIG_DONT_UNSET_ARRAY_INDEX = 'CONFIG_DONT_UNSET_ARRAY_INDEX';

    public const PARAMETER_PRODUCT_ATTR_BRAND = 'brand';
    public const PARAMETER_PRODUCT_ATTR_NAME_UNTRANSLATED = 'name_untranslated';
    public const PARAM_PRODUCT_ATTR_SIZE_UNTRANSLATED = 'size_untranslated';
    public const PARAM_PRODUCT_ATTR_SIZE = 'size';
    public const PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED = 'style_untranslated';
    public const PARAM_PRODUCT_ATTR_STYLE = 'style';
}
