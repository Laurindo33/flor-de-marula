<?php

namespace Tests\Concerns;

use Illuminate\Testing\TestResponse;

/**
 * Laravel's test client does not automatically carry cookies set by one
 * response into the next request (unlike a real browser). Features that
 * rely on the guest session id persisting across requests — like the
 * cart, which is looked up by session()->getId() — need the session
 * cookie forwarded manually between calls within the same test.
 */
trait InteractsWithGuestSession
{
    protected function carrySessionCookie(TestResponse $response): void
    {
        $cookieName = config('session.cookie');
        $cookie = $response->headers->getCookies()[0] ?? null;

        foreach ($response->headers->getCookies() as $candidate) {
            if ($candidate->getName() === $cookieName) {
                $cookie = $candidate;
                break;
            }
        }

        if ($cookie) {
            // The cookie's value as it appears in the Set-Cookie header is
            // already encrypted (that's what a real browser would store and
            // resend as-is) — withUnencryptedCookies() sends it verbatim
            // instead of encrypting it a second time.
            $this->withUnencryptedCookies([$cookie->getName() => $cookie->getValue()]);
        }
    }
}
