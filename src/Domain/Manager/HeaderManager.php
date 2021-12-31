<?php

namespace App\Domain\Manager;

use App\Application\Service\DateTimeService;
use App\Domain\Core\Interfaces\ManagerInterface;
use DateTime;
use KoenHoeijmakers\Headers\Header;
use Symfony\Component\HttpFoundation\Response;

final class HeaderManager implements ManagerInterface
{
    private $dateTime;

    public function __construct(DateTimeService $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function call(): array
    {
        return $this->header();
    }

    private function header(): array
    {
        $response = new Response();

        /*
         * Cache
         */
        //Sets the number of seconds after which the response should no longer be considered fresh.
        $response->setMaxAge(100);
        $response->setSharedMaxAge(200);

        $response->setLastModified((new DateTime())->add(($this->dateTime->getDateInterval('', '8600S'))));
        $response->setPrivate();
        $response->setProtocolVersion('1.1');
        $response->setStatusCode(Response::HTTP_PARTIAL_CONTENT, 'status code text');
        $response->setTtl(3600); // adjusts the Cache-Control/s-maxage

        // specific
        //discards any headers that MUST NOT be included in 304 NOT MODIFIED  responses
        (new Response())->setNotModified();

        /*
         * Vary
         */
        $response->headers->set(Header::VARY, ['Accept-Encoding', 'User-Agent'], true); //replace
        $response->setVary(Header::ACCEPT_ENCODING, true); // replace = true
        $response->setVary(Header::USER_AGENT);
        $response->setVary([Header::ACCEPT_ENCODING, Header::USER_AGENT]); // replace = true

        // @todo
        /*
        $response->setVary('Accept-Encoding');
        $response->setVary('User-Agent', false);
        $response->headers->set('Vary', 'Accept-Encoding, User-Agent');
        $response->headers->set('Vary', 'Accept-Encoding');
        $response->headers->set('Vary', 'User-Agent', false);
        $response->setVary('Accept-Encoding, User-Agent');
        $response->headers->set('Vary', 'Accept-Encoding');
        $response->headers->set('Vary', 'User-Agent');
        $response->setVary('Accept-Encoding');
        $response->setVary('User-Agent');
        $response->setVary(['Accept-Encoding', 'User-Agent']);
        $response->headers->set('Vary', ['Accept-Encoding', 'User-Agent']);*/

        return $response->headers->all();
    }
}
