# Upgrade From 1.x to 2.0

## Events

### Event Factories

**Likelihood Of Impact: Medium**

If you override the default handlers, update them to use
`Imdhemy\Purchases\Events\EventFactory`, which implements
`Imdhemy\Purchases\Contracts\EventFactory`.

The following provider-specific factories have been removed:

- `Imdhemy\Purchases\Events\AppStore\EventFactory`
- `Imdhemy\Purchases\Events\GooglePlay\EventFactory`

## Validation

### Receipt Validation Rule

**Likelihood Of Impact: Medium**

The `Imdhemy\Purchases\Http\Rules\ValidReceipt` rule has been removed.

## Dates

### Time Value Object

**Likelihood Of Impact: Medium**

The `Time` class and its accessors are deprecated and will be removed in the next
major release. They remain available during the deprecation period.

- Replace subscription `getExpiryTime()` with `getExpiresAt(): DateTimeImmutable`,
  also added to `SubscriptionContract`.
- Replace notification and renewal-status event `getAutoRenewStatusChangeDate()`
  with `getAutoRenewStatusChangedAt(): ?DateTimeImmutable`.
- Replace direct use of `Imdhemy\Purchases\ValueObjects\Time` with `DateTimeImmutable`.
