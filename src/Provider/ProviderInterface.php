<?php

/*
 * Copyright (c) 2023.
 */

namespace Oasin\BitcoinConverter\Provider;

interface ProviderInterface
{
    /**
     * Get rate of currency code.
     *
     * @param  string  $currencyCode
     * @return float
     */
    public function getRate($currencyCode);
}
