<?php

namespace App\Domain\Model\Header;

use KoenHoeijmakers\Headers\Header;

/**
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Caching
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers#caching
 * 
 * Successful results of a retrieval request: a 200 (OK) response.
 * Incomplete results: a 206 (Partial Content) response.
 * Permanent redirects: a 301 (Moved Permanently) response.
 * Error responses: a 404 (Not Found) result page.
 */
class CacheHeader
{
    const AGE           = Header::AGE; // The time since a response was generated (fresh <> stale)

    /**
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control
     * 
     * REQUEST
     *  max-age, max-stale, min-fresh, no-cache, no-store, no-transform, only-if-cached, stale-if-error
     * 
     * RESPONSE
     *  max-age, s-maxage (proxy, cdn), no-cache, no-store, no-transform, must-revalidate, proxy-revalidate, must-understand, 
     *  private (browser), public, immutable, stale-while-revalidate, stale-if-error
     */

    /*
     * max-age=N response directive indicates that the response remains fresh until N seconds after the response is generated
     */
    const CACHE_CONTROL = Header::CACHE_CONTROL;

    const EXPIRES       = Header::EXPIRES;

    const PRAGMA        = Header::PRAGMA;
}
