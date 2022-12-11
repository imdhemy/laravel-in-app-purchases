<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Handlers;

use Illuminate\Http\Request;
use Imdhemy\AppStore\Jws\JsonWebSignature;
use Imdhemy\AppStore\Jws\JwsParser;
use Imdhemy\AppStore\Jws\JwsVerifier;

class JwsService implements JwsServiceInterface
{
    private JwsParser $jwsParser;

    private JwsVerifier $jwsVerifier;

    private Request $request;

    private ?JsonWebSignature $jws = null;

    public function __construct(JwsParser $jwsParser, JwsVerifier $jwsVerifier, Request $request)
    {
        $this->jwsParser = $jwsParser;
        $this->jwsVerifier = $jwsVerifier;
        $this->request = $request;
    }

    /**
     * Verify the JWS.
     */
    public function verify(): bool
    {
        return $this->jwsVerifier->verify($this->jws());
    }

    private function jws(): JsonWebSignature
    {
        if (is_null($this->jws)) {
            $this->jws = $this->jwsParser->parse((string)$this->request->get('signedPayload'));
        }

        return $this->jws;
    }

    /**
     * Parses the string into a JWS.
     */
    public function parse(): JsonWebSignature
    {
        return $this->jws();
    }
}
