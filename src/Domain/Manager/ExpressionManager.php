<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class ExpressionManager implements ManagerInterface
{
    public function call(): array
    {
        return [
            'expression' => $this->expression(),
        ];
    }

    private function expression(): string
    {
        $language = new ExpressionLanguage();

        return $language->evaluate('"\\\\"');
    }
}
