<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Dependency;

use Generated\Shared\Transfer\QuoteTransfer;

interface EnhancedEcommerceCartConnectorToCartClientInterface
{
    /**
     * Specification:
     *  - Gets current quote from session
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote(): QuoteTransfer;
}
