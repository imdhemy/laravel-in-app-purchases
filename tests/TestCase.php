<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests;

use Faker\Factory;
use Imdhemy\Purchases\ServiceProviders\LiapServiceProvider;
use Imdhemy\Purchases\Tests\Doubles\LiapTestProvider;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\UnencryptedToken;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * Test Case
 * All test cases that requires a laravel app instance should extend this class.
 */
class TestCase extends Orchestra
{
    protected Faker $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = new Faker(Factory::create());
    }

    protected function getPackageProviders($app): array
    {
        return [
            LiapServiceProvider::class,
            LiapTestProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * Get the path to fixtures dir.
     */
    protected function fixturesDir(?string $path = null): string
    {
        $assetsPath = __DIR__.'/Doubles/fixtures';

        if ($path) {
            return $assetsPath.'/'.$path;
        }

        return $assetsPath;
    }

    /**
     * Generates a fake p8 key.
     */
    protected function generateP8Key(): string
    {
        $key = 'MHQCAQEEIPKsJBiuilVdbtkxtPpSp0LLlUeqCmwx6Ss2OBvIhTbioAcGBSuBBAAK
oUQDQgAEacH/sdtom9kl/0AvHFNNuoxnUWzLwWXf70qH2O1FDrvjDXY2aC7NFg9t
WtcP+PnScROkjnSv6H6A6ekLVAzQYg==';

        $filename = 'privateKey-'.time().'.p8';
        $path = $this->fixturesDir($filename);

        if (! file_exists($path)) {
            $contents = "-----BEGIN EC PRIVATE KEY-----\n".$key."\n-----END EC PRIVATE KEY-----";
            file_put_contents($path, $contents);
        }

        return $path;
    }

    /**
     * Deletes the given file is exists.
     */
    protected function deleteFile(string $path): void
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * @param array<string, string> $claims
     */
    protected function sign(array $claims): UnencryptedToken
    {
        $key = InMemory::base64Encoded('hiG8DlOKvtih6AxlZn5XKImZ06yu8I3mkOzaJrEuW8yAv8Jnkw330uMt8AEqQ5LB');

        return (new JwtFacade())->issue(
            new Sha256(),
            $key,
            static function (Builder $builder) use ($claims): Builder {
                foreach ($claims as $key => $value) {
                    $builder->withClaim($key, $value);
                }

                return $builder;
            }
        );
    }
}
