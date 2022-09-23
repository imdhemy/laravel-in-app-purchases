<?php

namespace Imdhemy\Purchases\Handlers;

use Illuminate\Http\Request;
use Imdhemy\AppStore\Jws\JsonWebSignature;
use Imdhemy\AppStore\Jws\JwsParser;
use Imdhemy\AppStore\Jws\JwsVerifier;

class JwsService implements JwsServiceInterface
{
    /**
     * @var JwsParser
     */
    private JwsParser $jwsParser;

    /**
     * @var JwsVerifier
     */
    private JwsVerifier $jwsVerifier;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var JsonWebSignature|null
     */
    private ?JsonWebSignature $jws = null;

    /**
     * @param JwsParser $jwsParser
     * @param JwsVerifier $jwsVerifier
     * @param Request $request
     */
    public function __construct(JwsParser $jwsParser, JwsVerifier $jwsVerifier, Request $request)
    {
        $this->jwsParser = $jwsParser;
        $this->jwsVerifier = $jwsVerifier;
        $this->request = $request;
    }

    /**
     * Verify the JWS
     *
     * @return bool
     */
    public function verify(): bool
    {
        return $this->jwsVerifier->verify($this->jws());
    }

    /**
     * @return JsonWebSignature
     */
    private function jws(): JsonWebSignature
    {
        if (is_null($this->jws)) {
            $this->jws = $this->jwsParser->parse($this->request->get('signedPayload'));
        }

        return $this->jws;
    }

    /**
     * Parses the string into a JWS
     *
     * @return JsonWebSignature
     */
    public function parse(): JsonWebSignature
    {
        return $this->jws();
    }
}
