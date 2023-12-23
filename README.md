# Paymob-Payment-Gateway

## Overview

The Paymob Payment Gateway PHP package is a convenient integration for developers seeking to integrate Paymob's payment services into their PHP applications. This package streamlines the process of initiating and managing payments, offering support for various payment methods, including mobile wallets and card payments.

## Features

- **Mobile Wallet Integration:** Initiate payments with popular mobile wallets.
- **Card Payments:** Seamless integration for card payments.
- **Authentication:** Obtain secure API tokens for communication with Paymob API.
- **Flexible Configuration:** Easily configure integration settings.
- **Order Management:** Efficiently manage order details for payment processing.

## Installation

Use [Composer](https://getcomposer.org/) to install the package:

```bash
composer require abdulbaset/paymob-payment-gateway
```

after installing the package you should include the namespace, see the following code:

```bash
use Abdulbaset\PaymentGateways\Paymob\PaymobPaymentMethod;
```

if you want to get the card url for payment, see the following code:

```bash
$invoice = new PaymobPaymentMethod();
$invoice->setApiKey($API_Key);
$invoice->setAmountCents(10.00 * 100);
$invoice->getCardPaymentsURL('0123456', '123456');
```

- for example,
  ![Screenshot 1](/media/getCardPaymentsURL.png)

if you want to get the mobile wallet url for payment, see the following code:

```bash
$invoice = new PaymobPaymentMethod();
$invoice->setApiKey($API_Key);
$invoice->setAmountCents(10.00 * 100);
$invoice->getMobileWalletsURL('0123456','01010101010');
```

- for example,
  ![Screenshot 1](/media/getMobileWalletsURL.png)

## License

This Paymob Payment Gateway PHP Package is open-source software licensed under the MIT License.
