<?php

namespace Imdhemy\Purchases\Http\Controllers;

use Illuminate\Http\Request;
use Imdhemy\Purchases\Http\Handlers\HandlerFactory;

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
     * @param Request $request
     */
    public function __invoke(HandlerFactory $handlerFactory, Request $request)
    {
        $handler = $handlerFactory->create($request->get('provider'));

        $handler->execute();
    }
}
