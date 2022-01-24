<?php

namespace Imdhemy\Purchases;

class SignatureVerifier
{
    /**
     * @param  string  $publicKey
     * @return string
     */
    private static function generatePublicKey(string $publicKey): string
    {
        $begin_public = "-----BEGIN PUBLIC KEY-----\n";
        $end_public = "-----END PUBLIC KEY-----\n";
        return $begin_public.chunk_split($publicKey, 64).$end_public;
    }

    /**
     * @param  string  $content
     * @param  string  $sign
     * @return bool
     */
    public static function verify(string $content, string $sign): bool
    {
        $pubKey = openssl_get_publickey(self::generatePublicKey(config('purchase.app_gallery_public_key')));
        return (bool)openssl_verify($content, base64_decode($sign), $pubKey, OPENSSL_ALGO_SHA256);
    }
}
