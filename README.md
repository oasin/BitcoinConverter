# Bitcoin Converter

This library helps fintech developers to convert bitcoin to fiat currency or to another cryptocurrency and vice versa.

Available exchange rates providers are:
* [Coinbase][link-coinbase-rates]
* [Coindesk][link-coindesk-rates]
* [Bitpay][link-bitpay-rates]

## Features

It is simple, lightweight, extensible, framework agnostic and fast.

* You can convert Bitcoin to any currency (ISO 4217 fiat or another cryptocurrency)
* You can convert any currency (ISO 4217 fiat or another cryptocurrency) to Bitcoin
* It supports different exchange rates providers: Coinbase, Coindesk, Bitpay
* It has baked-in caching (PSR16 compliant, swappable with your own or your framework's)

## Install

Lets begin by installing the library by Composer:

``` bash
$ composer require mitmelon/bitcoin-converter:dev-main
```

## Usage

#### You can then convert Bitcoin to any currency (ISO 4217 fiat or crypto) by:

``` php
use Oasin\BitcoinConverter\Converter;

$convert = new Converter; // uses Coinbase as default provider
echo $convert->toCurrency('USD', 0.5);
echo $convert->toCurrency('LTC', 0.5);
```

#### You can also convert any currency (ISO 4217 fiat or crypto) to Bitcoin:

``` php
use Oasin\BitcoinConverter\Converter;

$convert = new Converter;         // uses Coinbase as default provider
echo $convert->toBtc(100, 'USD');
echo $convert->toBtc(20, 'LTC');
```

and it also has its helper function for convenience:

``` php
// uses Coinbase as default provider
echo to_btc(100, 'USD');
echo to_btc(20, 'LTC');
```

#### You can use different exchange rates from providers:

``` php
use Oasin\BitcoinConverter\Converter;
use Oasin\BitcoinConverter\Provider\CoinbaseProvider;
use Oasin\BitcoinConverter\Provider\CoindeskProvider;
use Oasin\BitcoinConverter\Provider\BitpayProvider;

$convert = new Converter(new CoinbaseProvider);
$convert = new Converter(new CoindeskProvider);
$convert = new Converter(new BitpayProvider);
```
#### You can specify cache expire time (ttl) on provider by:

``` php
new CoinbaseProvider($httpClient, $psr16CacheImplementation, 5); // cache expires in 5mins, defaults to 60mins
```

## Contributing

Open for suggestions and requests. Please request through [issue][link-issue] or [pull requests][link-pull-request].

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[link-coinbase-rates]: https://www.coinbase.com/charts
[link-coindesk-rates]: https://www.coindesk.com/price
[link-bitpay-rates]: https://bitpay.com/bitcoin-exchange-rates