<?php

namespace App\Application;

use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class Response extends HttpFoundationResponse
{
    private $data;

    public function __construct(array $data, int $status = 200, array $headers = [])
    {
        $this->data = $data;
        parent::__construct('<placeholder>', $status, $headers);
    }

    public function getControllerData(): array
    {
        return $this->data;
    }
}
