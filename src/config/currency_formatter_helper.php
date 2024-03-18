<?php

/*
 * Copyright (c) 2023.
 */

use Oasin\BitcoinConverter\Exception\InvalidArgumentException;

if (!function_exists('format_to_currency')) {
    /**
     * Format to currency type.
     *
     * @param  string  $currencyCode
     * @param  float|int|string  $value
     * @return float|int|string
     */
    function format_to_currency(string $currencyCode, float|int|string $value): float|int|string
    {
        if (is_crypto_currency($currencyCode)) {
            return round($value, 8, PHP_ROUND_HALF_UP);
        }

        if (is_fiat_currency($currencyCode)) {
            return round($value, 2, PHP_ROUND_HALF_UP);
        }

        throw new InvalidArgumentException("Argument \$currencyCode not valid currency code, '{$currencyCode}' given.");
    }
}
