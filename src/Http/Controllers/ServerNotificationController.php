<?php

namespace Imdhemy\Purchases\Http\Controllers;

use Imdhemy\Purchases\Http\Handlers\HandlerFactory;
use Imdhemy\Purchases\Http\Requests\ServerNotificationRequest;

/**
 * Server notification controller
 *
 * This controller handles the incoming requests by the IAP service providers
 * and dispatches relevant events.
 */
class ServerNotificationController extends Controller
{
    /**
     * Handles the server notification request
     *
     * @param HandlerFactory $handlerFactory
     * @param ServerNotificationRequest $request
     */
    public function __invoke(HandlerFactory $handlerFactory, ServerNotificationRequest $request)
    {
        $handler = $handlerFactory->create($request->getProvider());

        $handler->execute();
    }
}
