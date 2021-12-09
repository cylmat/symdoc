<?php 

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use KoenHoeijmakers\Headers\Header;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class HeaderManager implements ManagerInterface
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function call(): array
    {        
        return $this->header();
    }

    private function header(): array
    {
        $response = new Response();

        $response->headers->set(Header::VARY, ['Accept-Encoding', 'User-Agent'], true); //replace

        $response->setVary(Header::ACCEPT_ENCODING, true); // replace = true
        $response->setVary(Header::USER_AGENT);

        $response->setVary(['Accept-Encoding', 'User-Agent']); // replace = true
        $response->setMaxAge(100); //Sets the number of seconds after which the response should no longer be considered fresh.

        return $response->headers->all();
    }
}