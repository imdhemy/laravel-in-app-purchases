<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Console;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Imdhemy\Purchases\Services\AppStoreTestNotificationService;
use Imdhemy\Purchases\Services\AppStoreTestNotificationServiceBuilder as ServiceBuilder;

/**
 * This command is used to request a test notification from Apple
 * It uses the configuration from the config file `liap.php`.
 */
class RequestTestNotificationCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'liap:apple:test-notification {--s|sandbox}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Request a test notification from Apple';

    /**
     * @var ServiceBuilder App Store test notification service builder
     */
    private ServiceBuilder $serviceBuilder;

    public function __construct(ServiceBuilder $serviceBuilder)
    {
        parent::__construct();

        $this->serviceBuilder = $serviceBuilder;
    }

    /**
     * Execute the console command.
     *
     * @throws \JsonException
     */
    public function handle(): int
    {
        try {
            $response = $this->buildService()->request();

            $content = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            $token = $content['testNotificationToken'];

            $this->info(sprintf('Test notification token: %s', $token));

            return self::SUCCESS;
        } catch (\RuntimeException|GuzzleException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }

    /**
     * Builds the AppStoreTestNotificationService.
     */
    private function buildService(): AppStoreTestNotificationService
    {
        $this->setPrivateKeyId();
        $this->setPrivateKey();
        $this->setIssuerId();
        $this->setBundleId();

        return $this->serviceBuilder->sandbox($this->option('sandbox'))->build();
    }

    /**
     * Set the private key ID.
     */
    private function setPrivateKeyId(): void
    {
        $privateKeyId = config('liap.appstore_private_key_id');

        if (null === $privateKeyId) {
            throw new \RuntimeException('The private key ID is not configured');
        }

        $this->serviceBuilder->privateKeyId($privateKeyId);
    }

    /**
     * Sets the private key.
     */
    private function setPrivateKey(): void
    {
        $privateKey = config('liap.appstore_private_key');

        if (null === $privateKey) {
            throw new \RuntimeException('The private key is not configured');
        }

        $this->serviceBuilder->privateKey(file_get_contents($privateKey));
    }

    /**
     * Sets the issuer ID.
     */
    private function setIssuerId(): void
    {
        $issuerId = config('liap.appstore_issuer_id');

        if (null === $issuerId) {
            throw new \RuntimeException('The issuer ID is not configured');
        }

        $this->serviceBuilder->issuerId($issuerId);
    }

    /**
     * Sets the bundle ID.
     */
    private function setBundleId(): void
    {
        $bundleId = config('liap.appstore_bundle_id');

        if (null === $bundleId) {
            throw new \RuntimeException('The bundle ID is not configured');
        }

        $this->serviceBuilder->bundleId($bundleId);
    }
}
