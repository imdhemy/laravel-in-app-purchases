# Upgrade From 1.x to 2.0

## Medium Impact Changes

### Events

In case you are overriding the default handlers, you should update the event factory class.

- The handlers now use a single event factory to create events. The `EventFactory` class which
  implements `\Imdhemy\Purchases\Contracts\EventFactory` is responsible for creating events.
    * The `Imdhemy\Purchases\Events\AppStore\EventFactory` class has been removed.
      Use `Imdhemy\Purchases\Events\EventFactory` instead.
    * The `Imdhemy\Purchases\Events\GooglePlay\EventFactory` class has been removed.
      Use `Imdhemy\Purchases\Events\EventFactory` instead.

### Validation Rules

The rule class `\Imdhemy\Purchases\Http\Rules\ValidReceipt` has been removed.
