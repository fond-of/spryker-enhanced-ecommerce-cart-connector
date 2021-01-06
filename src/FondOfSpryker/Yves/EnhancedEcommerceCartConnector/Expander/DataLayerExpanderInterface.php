<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCartConnector\Expander;

interface DataLayerExpanderInterface
{
    /**
     * @param string $page
     * @param array $twigVariableBag
     * @param array $dataLayer
     *
     * @return array
     */
    public function expand(string $page, array $twigVariableBag, array $dataLayer): array;
}
