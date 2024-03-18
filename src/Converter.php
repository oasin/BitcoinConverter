<?php

/*
 * Copyright (c) 2023.
 */

namespace Oasin\BitcoinConverter;

use Oasin\BitcoinConverter\Exception\InvalidArgumentException;
use Oasin\BitcoinConverter\Provider\CoinbaseProvider;
use Oasin\BitcoinConverter\Provider\ProviderInterface;

class Converter
{
    /**
     * Provider instance.
     *
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * Create Converter instance.
     *
     * @param  ProviderInterface|null  $provider
     */
    public function __construct(?ProviderInterface $provider = null)
    {
        if (is_null($provider)) {
            $provider = new CoinbaseProvider;
        }

        $this->provider = $provider;
    }

    /**
     * Convert Bitcoin amount to a specific currency.
     *
     * @param  string  $currencyCode
     * @param  float  $btcAmount
     * @return float
     */
    public function toCurrency(string $currencyCode, float $btcAmount): float
    {
        $rate = $this->getRate($currencyCode);

        $value = $this->computeCurrencyValue($btcAmount, $rate);

        return $this->formatToCurrency($currencyCode, $value);
    }

    /**
     * Get rate of currency.
     *
     * @param  string  $currencyCode
     * @return float
     */
    protected function getRate(string $currencyCode): float
    {
        return $this->provider->getRate($currencyCode);
    }

    /**
     * Compute currency value.
     *
     * @param  float|int|string  $btcAmount
     * @param  float|int|string  $rate
     * @return float|int|string
     */
    protected function computeCurrencyValue(float|int|string $btcAmount, float|int|string $rate): float|int|string
    {
        if (!is_numeric($btcAmount)) {
            throw new InvalidArgumentException("Argument \$btcAmount should be numeric, '{$btcAmount}' given.");
        }

        return $btcAmount * $rate;
    }

    /**
     * Format value based on currency.
     *
     * @param  string  $currencyCode
     * @param  float|int|string  $value
     * @return float|int|string
     */
    protected function formatToCurrency($currencyCode, $value): float|int|string
    {
        return format_to_currency($currencyCode, $value);
    }

    /**
     * Convert currency amount to Bitcoin.
     *
     * @param  float|int|string  $amount
     * @param  string  $currencyCode
     * @return float|int|string
     */
    public function toBtc(float|int|string $amount, string $currencyCode): float|int|string
    {
        $rate = $this->getRate($currencyCode);

        $value = $this->computeBtcValue($amount, $rate);

        return $this->formatToCurrency('BTC', $value);
    }

    /**
     * Compute Bitcoin value.
     *
     * @param  float|int|string  $amount
     * @param  float|int|string  $rate
     * @return float|int
     *
     * @throws InvalidArgumentException
     */
    protected function computeBtcValue(float|int|string $amount, float|int|string $rate): float|int
    {
        if (!is_numeric($amount)) {
            throw new InvalidArgumentException("Argument \$amount should be numeric, '{$amount}' given.");
        }

        return $amount / $rate;
    }
}
