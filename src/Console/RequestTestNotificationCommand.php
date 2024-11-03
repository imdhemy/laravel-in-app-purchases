<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Console;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Imdhemy\Purchases\Services\AppStoreTestNotificationServiceBuilder as AppStoreTestBuilder;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * This command is used to request a test notification from Apple
 * It uses the configuration from the config file `liap.php`.
 */
class RequestTestNotificationCommand extends Command
{
    protected $signature = 'liap:apple:test-notification {--s|sandbox}';
    protected $description = 'Request a test notification from Apple';

    private AppStoreTestBuilder $testBuilder;

    public function __construct(AppStoreTestBuilder $testBuilder)
    {
        $this->testBuilder = $testBuilder;

        parent::__construct();
    }

    public function handle(): int
    {
        try {
            $config = (array)config('liap');
            $config['sandbox'] = (bool)$this->option('sandbox');
            $testService = $this->testBuilder->of($config)->build();

            $this->info(
                sprintf(
                    'Test notification token: %s',
                    $this->parseToken($testService->request())
                )
            );

            return self::SUCCESS;
        } catch (RuntimeException|GuzzleException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }

    private function parseToken(ResponseInterface $response): string
    {
        $content = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        assert(is_array($content) && array_key_exists('testNotificationToken', $content));

        $token = $content['testNotificationToken'];
        assert(is_string($token));

        return $token;
    }
}
