<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Converter;

interface IntegerToDecimalConverterInterface
{
    /**
     * @param int $value
     *
     * @return float
     */
    public function convert($value): float;
}
