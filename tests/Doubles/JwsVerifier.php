<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Doubles;

use Imdhemy\AppStore\Jws\JsonWebSignature;
use Imdhemy\AppStore\Jws\Jws;
use Imdhemy\AppStore\Jws\JwsVerifier as JwsVerifierInterface;

/**
 * JWS Verifier double
 * This class is used for testing purposes.
 */
class JwsVerifier implements JwsVerifierInterface
{
    private bool $shouldPass;

    public function __construct(bool $shouldPass = true)
    {
        $this->shouldPass = $shouldPass;
    }

    /**
     * Creates a new JWS verifier instance.
     *
     * @return static
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * Verifies the JWS.
     *
     * @param Jws $jws
     */
    public function verify(JsonWebSignature $jws): bool
    {
        return $this->shouldPass;
    }

    public function setShouldPass(bool $shouldPass): JwsVerifier
    {
        $this->shouldPass = $shouldPass;

        return $this;
    }
}
