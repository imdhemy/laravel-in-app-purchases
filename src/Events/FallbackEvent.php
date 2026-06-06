<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events;

/**
 * Represents a fallback event for in-app purchases.
 *
 * This class extends PurchaseEvent to handle cases where a specific event type is not recognized,
 * acting as a default or fallback event in purchase event handling.
 */
final class FallbackEvent extends PurchaseEvent
{
}
