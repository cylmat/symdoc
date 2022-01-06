<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;

// @codingStandardsIgnoreStart
class Bar { private $foo = 'private binded accessed...'; }

final class PhpManager implements ManagerInterface
{
    public function call(array $context = []): array
    {
        return [
            'closure' => $this->closureFunc(),
            'foreach' => $this->foreachFunc(),
        ];
    }

    private function foreachFunc()
    {
        $alpha = [1, 2, 3];

        foreach ($alpha as &$cam) {}
        foreach ($alpha as $cam) {}

        return ($alpha); // output [1, 2, 2]
    }

    private function closureFunc(): string
    {
        $getter = function() {
            return function() {
                return $this->foo;
            };
        };

        return $getter()->bindTo(new Bar(), Bar::class)();
    }
}
// @codingStandardsIgnoreEnd