<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class ExpressionManager implements ManagerInterface
{
    public function call(): array
    {
        return $this->expression();
    }

    /**
     * "PHP sandboxed..."
     */
    private function expression(): array
    {
        $language = new ExpressionLanguage();
        $object = new class() { public $num = 5, $txt = 'alpha', $func = "echo 'test'"; };

        return [
            '1_2_obj_evaluate' => $language->evaluate('1 + 2 + myobj.num', ['myobj' => $object]),
            '1_2_compile' => $language->compile('1 + 2', []), // compiled into php
            '1_2_parse' => $language->parse('1 + 2', []), 
        ];
    }
}
