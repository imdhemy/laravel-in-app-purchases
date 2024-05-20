<div align="center">
    <p><img width="450" src="cover.png" alt="Laravel In-app Purchase cover"></p>
    <p>
       <img alt="Packagist PHP Version Support" src="https://img.shields.io/packagist/php-v/imdhemy/laravel-purchases">
        <a href="https://packagist.org/packages/imdhemy/laravel-purchases"><img src="https://img.shields.io/packagist/v/imdhemy/laravel-purchases.svg?style=flat-square" 
alt="Latest Version on Packagist"></a>
       <a href="https://packagist.org/packages/imdhemy/laravel-purchases/stats"><img src="https://img.shields.io/packagist/dt/imdhemy/laravel-purchases.svg?style=flat-square" 
alt="Total Downloads"></a>
        <a href="https://github.com/imdhemy/laravel-in-app-purchases/commits/"><img alt="GitHub last commit" src="https://img.shields.io/github/last-commit/imdhemy/laravel-in-app-purchases"></a>
        <a href="https://github.com/imdhemy/laravel-in-app-purchases/actions/workflows/ci.yml"><img src="https://github.com/imdhemy/laravel-in-app-purchases/actions/workflows/ci.yml/badge.svg" alt="CI"></a>
    </p>
    <p> ✅ App Store ✅ Google Play </p>

</div>

# Laravel In-App purchase

Google Play and App Store provide the In-App Purchase (IAP) services. IAP can be used to sell a variety of content,
including subscriptions, new features, and services. The purchase event and the payment process occurs on and handled by
the mobile application (iOS and Android), then your backend needs to be informed about this purchase event to deliver
the purchased product or update the user's subscription state.

**Laravel In-App purchase** comes to help you to parse and validate the purchased products and handle the different
states of a subscription, like New subscription , auto-renew, cancellation, expiration and etc.

## Installation

Use composer to install the package:

```bash
composer require imdhemy/laravel-purchases
```

## Sponsor this project 🙏

When I started this project, I used to have an Apple Developer Program membership, but now I don't have it anymore.
The membership is required to test the Apple IAP Notifications, and it costs $99/year. If you find this package useful and
you want to support this work, click on the [sponsor button](https://github.com/sponsors/imdhemy) to the right.


## Documentation

The documentation is available on [Liap manual](https://imdhemy.com/laravel-iap-docs/).

## License

Laravel In-App purchase is licensed under the [MIT license](./LICENSE.md).
