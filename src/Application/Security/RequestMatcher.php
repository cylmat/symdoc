<?php

namespace App\Application\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

// used for firewall
class RequestMatcher implements RequestMatcherInterface
{
    public function matches(Request $request)
    {
        dump($request->attributes->get('_route'));
        return true;
    }
}
