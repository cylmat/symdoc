<?php

namespace App\Application\Logger\Formatter;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;

class TextFormatter extends LineFormatter implements FormatterInterface
{
    public function format(array $record): string
    {
        $record['message'] .= ' msg';
        return parent::format($record);
    }

    public function formatBatch(array $records): string
    {
        return parent::formatBatch($records);
    }
}
