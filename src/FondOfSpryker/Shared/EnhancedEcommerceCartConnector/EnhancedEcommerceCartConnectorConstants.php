<?php

namespace FondOfSpryker\Shared\EnhancedEcommerceCartConnector;

interface EnhancedEcommerceCartConnectorConstants
{
    /**
     * @var string
     */
    public const PAGE_TYPE = 'cart';

    /**
     * @var string
     */
    public const EVENT = 'genericEvent';

    /**
     * @var string
     */
    public const EVENT_CATEGORY = 'ecommerce';

    /**
     * @var string
     */
    public const EVENT_ACTION_ADD_TO_CART = 'addToCart';

    /**
     * @var string
     */
    public const EVENT_ACTION_REMOVE_FROM_CART = 'removeFromCart';

    /**
     * @var string
     */
    public const CONFIG_DONT_UNSET_ARRAY_INDEX = 'CONFIG_DONT_UNSET_ARRAY_INDEX';

    /**
     * @var string
     */
    public const PARAM_PRODUCT = 'product';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ATTR_BRAND = 'brand';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ATTR_NAME_UNTRANSLATED = 'name_untranslated';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ATTR_SIZE_UNTRANSLATED = 'size_untranslated';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED = 'model_untranslated';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ATTR_SIZE = 'size';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ATTR_MODEL = 'model';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED = 'style_untranslated';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ATTR_STYLE = 'style';

    /**
     * @var string
     */
    public const ADD_TO_CART_PLUGIN_VALID_PAGE_TYPES = 'ADD_TO_CART_PLUGIN_VALID_PAGE_TYPES';

    /**
     * @var string
     */
    public const ADD_TO_CART_FORM_ID = 'ADD_TO_CART_FORM_ID';
}
