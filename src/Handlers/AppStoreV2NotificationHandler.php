<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Handlers;

use Illuminate\Support\Facades\Log;
use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\Purchases\ServerNotifications\AppStoreV2ServerNotification;

/**
 * Class AppStoreV2NotificationHandler
 * This class is used to handle AppStore V2 notifications.
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

        if ($serverNotification->isTest()) {
            $signedPayload = (string)$this->request->get('signedPayload');
            Log::info(
                'AppStoreV2NotificationHandler: Test notification received '.
                $signedPayload
            );

            return;
        }

        $event = $this->eventFactory->create($serverNotification);
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
