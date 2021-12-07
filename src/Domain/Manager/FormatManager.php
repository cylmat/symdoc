<?php 

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use DOMDocument;
use DOMXPath;

final class FormatManager implements ManagerInterface
{
    public function call(): array
    {        
        $xpathValue = $this->xpath();

        return [
            $xpathValue
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
}