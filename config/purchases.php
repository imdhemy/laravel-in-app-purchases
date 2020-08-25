<?php

    /*
    |--------------------------------------------------------------------------
    | Laravel In-app Purchases Configuration
    |--------------------------------------------------------------------------
    | To complete the validation process, start by creating a service account.
    | After downloading your google application credentials json file, move it
    | to the storage path root/storage/google-app-credentials.json
    |
    | To learn more: https://developers.google.com/identity/protocols/OAuth2ServiceAccount
    | Guide: https://stackoverflow.com/a/24365026/1248595
    |
    */

return [

    /*
     |-------------------------------------------------------------------------
     | Google Application Credentials file path
     |-------------------------------------------------------------------------
     |
     | This value the real path of the generated credentials file required for
     | Google Service Account to access your enabled APIS.
     |
     | To learn more: https://developers.google.com/identity/protocols/OAuth2ServiceAccount
     | Guide: https://stackoverflow.com/a/24365026/1248595
     */

    'google_app_credentials' => env('GOOGLE_APPLICATION_CRED', storage_path('google-app-credentials.json')),

    /**
     |-------------------------------------------------------------------------
     | Google Play Package Name
     |-------------------------------------------------------------------------
     |
     | The package name of the application for which this subscription was
     | purchased (for example, 'com.some.thing').
     */

    'google_play_package' => env('GOOGLE_PLAY_PACKAGE', 'com.some.thing'),

    /**
    |-------------------------------------------------------------------------
    | Sandbox status (iOS only)
    |-------------------------------------------------------------------------
    | You can create a sandbox account to test in-app purchases before you make your app available.
    |
    | To learn more: https://help.apple.com/app-store-connect/#/dev8b997bee1
    |
     */
    'ios_in_sandbox' => true,
];
