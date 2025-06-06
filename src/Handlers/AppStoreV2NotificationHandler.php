<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Handlers;

use Illuminate\Support\Facades\Log;
use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\Purchases\Domain\Event\AppStoreNotificationReceivedEvent;
use Imdhemy\Purchases\Domain\Model\AppStoreNotificationPayload;
use Imdhemy\Purchases\ServerNotifications\AppStoreV2ServerNotification;

/**
 * Class AppStoreV2NotificationHandler
 * This class is used to handle App Store V2 notifications.
 */
class AppStoreV2NotificationHandler extends AbstractNotificationHandler
{
    protected JwsServiceInterface $jwsService;

    public function __construct(HandlerHelpersInterface $helpers, JwsServiceInterface $jwsService)
    {
        $this->jwsService = $jwsService;

        parent::__construct($helpers);
    }

    /**
     * @psalm-suppress MissingReturnType - @todo: fix missing return type
     */
    protected function handle()
    {
        $decodedPayload = V2DecodedPayload::fromJws($this->jwsService->parse());
        $serverNotification = AppStoreV2ServerNotification::fromDecodedPayload($decodedPayload);
        $signedPayload = (string)$this->request->get('signedPayload');
        $appStoreNotificationPayload = new AppStoreNotificationPayload($signedPayload);

        if ($serverNotification->isTest()) {
            Log::info(
                'AppStoreV2NotificationHandler: Test notification received '.
                $signedPayload
            );

            $event = new AppStoreNotificationReceivedEvent(payload: $appStoreNotificationPayload);
            event($event);

            return;
        }

        $legacyEvent = $this->eventFactory->create($serverNotification);
        event($legacyEvent);

        $event = new AppStoreNotificationReceivedEvent(payload: $appStoreNotificationPayload);
        event($event);
    }

    protected function isAuthorized(): bool
    {
        return parent::isAuthorized() && $this->jwsService->verify();
    }

    /**
     * @return string[][]
     */
    protected function rules(): array
    {
        return [
            'signedPayload' => ['required', 'string'],
        ];
    }
}
