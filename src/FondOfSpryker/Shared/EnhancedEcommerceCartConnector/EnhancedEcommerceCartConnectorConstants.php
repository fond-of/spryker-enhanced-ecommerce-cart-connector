<?php

namespace FondOfSpryker\Shared\EnhancedEcommerceCartConnector;

interface EnhancedEcommerceCartConnectorConstants
{
    public const PAGE_TYPE = 'cart';

    public const EVENT = 'genericEvent';
    public const EVENT_CATEGORY = 'ecommerce';
    public const EVENT_ACTION_ADD_TO_CART = 'addToCart';
    public const EVENT_ACTION_REMOVE_FROM_CART = 'removeFromCart';

    public const CONFIG_DONT_UNSET_ARRAY_INDEX = 'CONFIG_DONT_UNSET_ARRAY_INDEX';

    public const PARAM_PRODUCT = 'product';
    public const PARAM_PRODUCT_ATTR_BRAND = 'brand';
    public const PARAM_PRODUCT_ATTR_NAME_UNTRANSLATED = 'name_untranslated';
    public const PARAM_PRODUCT_ATTR_SIZE_UNTRANSLATED = 'size_untranslated';
    public const PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED = 'model_untranslated';
    public const PARAM_PRODUCT_ATTR_SIZE = 'size';
    public const PARAM_PRODUCT_ATTR_MODEL = 'model';
    public const PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED = 'style_untranslated';
    public const PARAM_PRODUCT_ATTR_STYLE = 'style';

    public const ADD_TO_CART_PLUGIN_VALID_PAGE_TYPES = 'ADD_TO_CART_PLUGIN_VALID_PAGE_TYPES';
}
