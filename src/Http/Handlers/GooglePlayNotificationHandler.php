<?php

namespace Imdhemy\Purchases\Http\Handlers;


use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\GooglePlay\DeveloperNotifications\SubscriptionNotification;
use Imdhemy\Purchases\Contracts\NotificationHandlerContract;
use Imdhemy\Purchases\Events\GooglePlay\EventFactory as GooglePlayEventFactory;
use Imdhemy\Purchases\ServerNotifications\GoogleServerNotification;

/**
 * Google Play notification handler
 *
 * Handles Real time developer notifications sent by google play.
 * Dispatches the Google Play event related to the notification type.
 */
class GooglePlayNotificationHandler implements NotificationHandlerContract
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Factory
     */
    private $validator;

    /**
     * @param Request $request
     * @param Factory $validator
     */
    public function __construct(Request $request, Factory $validator)
    {
        $this->request = $request;
        $this->validator = $validator;
    }

    /**
     * Executes the handler
     *
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function execute()
    {
        $this->authorize();
        $this->validate();

        $data = $this->request->get('message')['data'];

        if (!$this->isParsable($data)) {
            Log::info(sprintf('Google Play malformed RTDN: %s', json_encode($this->request->all())));

            return;
        }

        $developerNotification = DeveloperNotification::parse($data);
        $googleNotification = new GoogleServerNotification($developerNotification);

        if ($developerNotification->isTestNotification()) {
            $version = $developerNotification->getPayload()->getVersion();
            Log::info(sprintf('Google Play Test Notification, version: %s', $version));
        }

        if ($developerNotification->getPayload() instanceof SubscriptionNotification) {
            $event = GooglePlayEventFactory::create($googleNotification);
            event($event);
        }
    }

    /**
     * @throws AuthorizationException
     */
    protected function authorize()
    {
        if (!$this->isAuthorized()) {
            throw new  AuthorizationException();
        }
    }

    /**
     * @return bool
     */
    protected function isAuthorized(): bool
    {
        return true;
    }

    /**
     * @throws ValidationException
     */
    protected function validate(): void
    {
        $this->validator->make($this->request->all(), $this->rules())->validate();
    }

    /**
     * @return string[][]
     */
    protected function rules(): array
    {
        return [
          'message' => ['required', 'array'],
          'message.data' => ['required'],
        ];
    }

    /**
     * @param string $data
     *
     * @return bool
     */
    protected function isParsable(string $data): bool
    {
        $decodedData = json_decode(base64_decode($data), true);

        return !is_null($decodedData);
    }
}
