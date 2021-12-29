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
    public const AGE           = Header::AGE; // The time since a response was generated (fresh <> stale)

    /**
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control
     *
     * REQUEST
     *  max-age, max-stale, min-fresh, no-cache, no-store, no-transform, only-if-cached, stale-if-error
     *
     * RESPONSE
     *  max-age, s-maxage (proxy, cdn), no-cache, no-store, no-transform,
     *    must-revalidate, proxy-revalidate, must-understand,
     *  private (browser), public, immutable, stale-while-revalidate, stale-if-error
     */

    /*
     * max-age=N    the response remains fresh until N seconds after the response is generated
     * s-maxage=N   how long the response is fresh for (similar to max-age) — but it is specific to shared caches
     *              ignore max-age when it is present
     * no-cache     the response can be stored in caches, but must be validated with the origin server before each reuse
     *              <> dont' cache (no-store) because it cache but revalidate each time
     * must-revalidate the response can be stored in caches and reused while fresh.
     *              Once it becomes stale, it must be validated again before reuse
     *                  if not (disconnected), it return a 504 (Gateway Timeout)
     *              Typically, must-revalidate is used with max-age.
     * proxy-revalidate equivalent of must-revalidate, but specifically for shared caches only
     * no-store     indicates that any caches of any kind (private or shared) should not store this response
     */
    public const CACHE_CONTROL = Header::CACHE_CONTROL;

    public const EXPIRES       = Header::EXPIRES;

    public const PRAGMA        = Header::PRAGMA;
}
