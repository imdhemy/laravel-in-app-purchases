<?php


namespace Imdhemy\Purchases\GooglePlay;

use Google\Auth\ApplicationDefaultCredentials;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Imdhemy\Purchases\Exceptions\CouldNotCreateGoogleClient;

class ClientFactory
{
    public const SCOPE_ANDROID_PUBLISHER = 'https://www.googleapis.com/auth/androidpublisher';

    /**
     * @param array $scopes
     * @return Client
     * @throws CouldNotCreateGoogleClient
     */
    public static function create(array $scopes = []): Client
    {
        self::checkCredentials();

        $stack = self::createMiddleware($scopes);

        return new Client([
            'handler' => $stack,
            'base_uri' => 'https://www.googleapis.com',
            'auth' => 'google_auth',
        ]);
    }

    /**
     * @throws CouldNotCreateGoogleClient
     */
    private static function checkCredentials(): void
    {
        $googleAppCredentials = config('purchases.google_app_credentials');
        if (is_null($googleAppCredentials)) {
            throw CouldNotCreateGoogleClient::credentialsNotFound();
        }
        putenv("GOOGLE_APPLICATION_CREDENTIALS=$googleAppCredentials");
    }

    /**
     * @param array $scopes
     * @return HandlerStack
     */
    private static function createMiddleware(array $scopes): HandlerStack
    {
        $middleware = ApplicationDefaultCredentials::getMiddleware($scopes);
        $stack = HandlerStack::create();
        $stack->push($middleware);

        return $stack;
    }
}
