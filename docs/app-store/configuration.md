# App Store configuration
In this document I'll show you how to config this package to work with App Store. 

## App Store Password
Apple's [App-Specific Shared Secret](https://developers.facebook.com/docs/app-events/getting-started-app-events-ios/app-shared-secret/) is a unique key to receive receipts for your app's auto-renewable subscriptions. This key allows you to verify these in-app purchases.

Open the config file `config/purchase.php`, you'll notice a configuration key `appstore_password` which gets its value from the environment variable `APPSTORE_PASSWORD`. You need to update your `.env` file to add the App Store password key and value.

