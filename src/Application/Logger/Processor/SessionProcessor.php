<?php

namespace App\Application\Logger\Processor;

use Monolog\Processor\ProcessorInterface;

# monolog.processor
class SessionProcessor implements ProcessorInterface
{
    public function __invoke(array $record)
    {
        $record['myextra'] = 'process' . (new \DateTime())->format('dmY');
        return $record;
    }
}
