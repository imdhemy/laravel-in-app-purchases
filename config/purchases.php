<?php

return [
    'google_app_credentials' => env('GOOGLE_APPLICATION_CRED', null),
    'google_play_package' => env('GOOGLE_PLAY_PACKAGE', storage_path('google-app-credentials.json')),
];
