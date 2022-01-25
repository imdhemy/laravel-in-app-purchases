<?php

namespace Imdhemy\Purchases;

use phpseclib3\Crypt\RSA;

class SignatureVerifier
{
    private const ALGORITHMS = [
        'SHA256WithRSA' => 'SHA256WithRSA',
        'SHA256WithRSA/PSS' => 'SHA256WithRSA/PSS',
    ];

    /**
     * @return string
     */
    private static function getPublicKey(): string
    {
        $begin_public = "-----BEGIN PUBLIC KEY-----\n";
        $end_public = "-----END PUBLIC KEY-----\n";
        return $begin_public.chunk_split(config('purchase.app_gallery_public_key'), 64).$end_public;
    }

    /**
     * @param  string  $content
     * @param  string  $sign
     * @return bool
     */
    private static function verifySHA256WithRSA(string $content, string $sign): bool
    {
        $publicKey = openssl_get_publickey(self::getPublicKey());
        return (bool)openssl_verify($content, base64_decode($sign), $publicKey, OPENSSL_ALGO_SHA256);
    }

    /**
     * @param  string  $content
     * @param  string  $sign
     * @return bool
     */
    private static function verifySHA256WithRSAPSS(string $content, string $sign): bool
    {
        return RSA::loadPublicKey(self::getPublicKey())->verify($content, base64_decode($sign));
    }

    /**
     * @param  string  $content
     * @param  string  $sign
     * @param  string  $algorithm
     * @return bool
     */
    public static function verify(string $content, string $sign, string $algorithm): bool
    {
        switch (self::ALGORITHMS[$algorithm]) {
            case 'SHA256WithRSA':
                return self::verifySHA256WithRSA($content, $sign);
            default:
                return self::verifySHA256WithRSAPSS($content, $sign);
        }
    }
}
