<?php

/*
 * Copyright (c) 2023.
 */

use Oasin\BitcoinConverter\Converter;
use Oasin\BitcoinConverter\Provider\ProviderInterface;

if (!function_exists('to_currency')) {
    /**
     * Convert Bitcoin amount to a specific currency.
     *
     * @param  string  $currencyCode
     * @param  float  $btcAmount
     * @param  ProviderInterface|null  $provider
     * @return float
     */
    function to_currency($currencyCode, $btcAmount, $provider = null)
    {
        return (new Converter($provider))->toCurrency($currencyCode, $btcAmount);
    }
}

if (!function_exists('to_btc')) {
    /**
     * Convert currency amount to Bitcoin.
     *
     * @param  float  $amount
     * @param  $currencyCode
     * @param  null  $provider
     * @return float
     */
    function to_btc($amount, $currencyCode, $provider = null)
    {
        return (new Converter($provider))->toBtc($amount, $currencyCode);
    }
}
