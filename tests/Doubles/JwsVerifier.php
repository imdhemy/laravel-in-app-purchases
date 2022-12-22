<?php

namespace Imdhemy\Purchases\Tests\Doubles;

use Imdhemy\AppStore\Jws\JsonWebSignature;
use Imdhemy\AppStore\Jws\Jws;
use Imdhemy\AppStore\Jws\JwsVerifier as JwsVerifierInterface;

/**
 * JWS Verifier double
 * This class is used for testing purposes
 */
class JwsVerifier implements JwsVerifierInterface
{
    /**
     * @var bool
     */
    private bool $shouldPass;

    /**
     * @param bool $shouldPass
     */
    public function __construct(bool $shouldPass = true)
    {
        $this->shouldPass = $shouldPass;
    }

    /**
     * Creates a new JWS verifier instance
     *
     * @return static
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * Verifies the JWS
     *
     * @param Jws $jws
     *
     * @return bool
     */
    public function verify(JsonWebSignature $jws): bool
    {
        return $this->shouldPass;
    }

    /**
     * @param bool $shouldPass
     *
     * @return JwsVerifier
     */
    public function setShouldPass(bool $shouldPass): JwsVerifier
    {
        $this->shouldPass = $shouldPass;

        return $this;
    }
}
