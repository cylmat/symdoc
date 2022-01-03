<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use DOMDocument;
use DOMXPath;
use Symfony\Component\Yaml\Yaml;

final class FormatManager implements ManagerInterface
{
    public function call(array $context = []): array
    {
        return [
            'xpath' => $this->xpath(),
            'yaml' => $this->yaml()
        ];
    }

    private function xpath(): string
    {
        $dom = new DOMDocument();
        $dom->loadHTML('<body bgcolor="555"></body>');
        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query('*[local-name()="body"]');
        $value = $nodes->item(0)->getAttributeNode('bgcolor')->value;

        return $value;
    }

    private function yaml(): object
    {
        $yaml = Yaml::parse('1983-07-01', Yaml::PARSE_DATETIME);

        return $yaml;
    }
}
