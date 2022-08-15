# Changelog

All notable changes to `imdhemy/laravel-purchases` will be documented here.

## Versioning scheme

The first published version from The [Laravel IAP](https://github.com/imdhemy/laravel-in-app-purchases) and all first
party packages including
`imdhemy/google-play-billing` and `imdhemy/appstore-iap` are a pre-releases. All of them have the major version of `0.x`
.

Starting from version `1.x`, all published versions will have the same semantic version as of Laravel in-app purchase.

Minor and patch releases should never contain breaking changes. When referencing the Laravel IAP or its components from
your application or package, you should always use a version constraint such as `^1.0`, since major releases of Laravel
IAP do include breaking changes.

## Support policy

For LTS releases, starting from `Laravel IAP 1`, bug fixes provided for 2 years and security fixes are provided for 3
years. These releases provide the longest window of support and maintenance. For general releases, bug fixes are
provided for 18 months and security fixes are provided for 2 years.

| Version | Release    | Bug fixes until | Security fixes until |
|---------|------------|-----------------|----------------------|
| 0 (Pre) | 2020-11-26 | 2022-05-26      | 2022-11-26           |
| 1 (LTS) | 2022-08-15 | 2024-08-15      | 2025-08-15           |

## Version 0.x

It's a pre-release. We are creating new bugs to fix.

## Version 1.x

This version is a long-term support release and a complete rewrite of the package. There are many breaking
changes that are not backward compatible. You can still use the pre-release version `0.x` for a while until an
upgrade to `1.x` is available. It will be available on the [documentation site](https://imdhemy.com/laravel-iap-docs/).

This version supports receipt validation, server notifications, and mocking receipts 🎉. The next versions will
support App Store notifications v.2.
