# Mayar.id Unofficial SDK PHP
[![Latest Version](https://img.shields.io/github/release/reactmore-tech/mayar-headless-api.svg?style=flat-square)](https://github.com/reactmore-tech/mayar-headless-api/releases)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/reactmore-tech/mayar-headless-api.svg?style=flat-square)](https://packagist.org/packages/reactmore-tech/mayar-headless-api)

## Installation

```cli
composer require reactmore-tech/mayar-headless-api
```

## Usage

```php
require 'vendor/autoload.php';

$mayarId = new \ReactMoreTech\MayarHeadlessAPI\MayarProvider();
$mayarId->setProduction(false);
$mayarId->apiToken("xxxx");
$mayarId->webhookToken("xxxxxx");
```

OR

```php
$mayarId = new \ReactMoreTech\MayarHeadlessAPI\MayarProvider([
  'apiToken' => "xxxxxxxxxxxxx",
  'webhookToken' => "xxxxxxxxxxx",
  'isProduction' => false,
]);
```

## Mayar Headless API Example Method in WIKI

See Example on [WIKI](https://github.com/reactmore-tech/mayar-headless-api/wiki):

- [ ] Product
- [ ] Invoice
- [ ] Request Payment
- [X] Installment
- [X] Discount & Coupon
- [ ] Cart
- [X] Customer
- [X] Transaction
- [X] Webhook
- [X] Software License Code
- [X] SaaS Membership License

Note that this repository is currently under development, additional classes and endpoints being actively added.

## Contributions

We welcome community contributions to this repository. Please refer to the contribution guidelines to get started.

## Licensing

Licensed under the MIT license. See the [LICENSE](LICENSE) file for details.