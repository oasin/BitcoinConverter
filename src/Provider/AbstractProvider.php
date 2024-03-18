<?php

/*
 * Copyright (c) 2023.
 */

namespace Oasin\BitcoinConverter\Provider;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository;
use Illuminate\Filesystem\Filesystem;
use Oasin\BitcoinConverter\Exception\InvalidArgumentException;
use Oasin\BitcoinConverter\Exception\UnexpectedValueException;
use Psr\SimpleCache\CacheInterface;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * GuzzleHttp client instance.
     *
     * @var Client
     */
    protected Client $client;

    /**
     * Cache's time to live in minutes.
     *
     * @var int
     */
    protected int $cacheTTL;

    public null|Repository|CacheInterface $cache;

    /**
     * Create provider instance.
     *
     * @param  \GuzzleHttp\Client|null  $client
     * @param  \Psr\SimpleCache\CacheInterface|null  $cache
     * @param  int  $cacheTTL
     */
    public function __construct(?Client $client = null, ?CacheInterface $cache = null, int $cacheTTL = 60)
    {
        if (is_null($client)) {
            $client = new Client;
        }

        if (is_null($cache)) {
            $cache = new Repository(new FileStore(new Filesystem, project_root_path('cache')));
        }

        $this->client = $client;
        $this->cache = $cache;
        $this->cacheTTL = $cacheTTL;
    }

    /**
     * Get rate of currency.
     *
     * @param  string  $currencyCode
     * @return float
     */
    public function getRate($currencyCode): float
    {
        if (!is_currency_code($currencyCode)) {
            throw new InvalidArgumentException("Argument passed not a valid currency code, '{$currencyCode}' given.");
        }

        $exchangeRates = $this->getExchangeRates();

        if (!$this->isSupportedByProvider($currencyCode)) {
            throw new InvalidArgumentException("Argument \$currencyCode '{$currencyCode}' not supported by provider.");
        }

        return $exchangeRates[strtoupper($currencyCode)];
    }

    /**
     * Get exchange rates in associative array.
     *
     * @return array
     */
    protected function getExchangeRates(): array
    {
        if (empty($this->exchangeRates)) {
            $this->setExchangeRates($this->retrieveExchangeRates());
        }

        return $this->exchangeRates;
    }

    /**
     * Set exchange rates.
     *
     * @param  array  $exchangeRatesArray
     */
    protected function setExchangeRates($exchangeRatesArray)
    {
        $this->exchangeRates = $exchangeRatesArray;
    }

    /**
     * Retrieve exchange rates.
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function retrieveExchangeRates(): array
    {
        if ($this->cache->has($this->cacheKey)) {
            return $this->cache->get($this->cacheKey);
        }

        $exchangeRatesArray = $this->parseToExchangeRatesArray($this->fetchExchangeRates());

        $this->cache->set($this->cacheKey, $exchangeRatesArray, $this->cacheTTL);

        return $exchangeRatesArray;
    }

    /**
     * Parse retrieved JSON data to exchange rates associative array.
     * i.e. ['BTC' => 1, 'USD' => 4000.00, ...]
     *
     * @param  string  $rawJsonData
     * @return array
     */
    abstract protected function parseToExchangeRatesArray($rawJsonData): array;

    /**
     * Fetch exchange rates json data from API endpoint.
     *
     * @return string
     *
     * @throws GuzzleException
     */
    protected function fetchExchangeRates()
    {
        $response = $this->client->request('GET', $this->apiEndpoint);

        if ($response->getStatusCode() != 200) {
            throw new UnexpectedValueException('Not OK response received from API endpoint.');
        }

        return $response->getBody();
    }

    /**
     * Check if currency code supported by provider.
     *
     * @param  string  $currencyCode
     * @return bool
     */
    protected function isSupportedByProvider($currencyCode)
    {
        return in_array(strtoupper($currencyCode), array_keys($this->exchangeRates));
    }
}
