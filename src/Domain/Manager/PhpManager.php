<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;

class Bar
{
    private $foo = 'private binded accessed...';
}

// @codingStandardsIgnoreStart
final class PhpManager implements ManagerInterface
// @codingStandardsIgnoreEnd
{
    public function call(array $context = []): array
    {
        return [
            'closure' => $this->closureFunc(),
            'foreach' => $this->foreachFunc(),
            'float' => $this->float(),
        ];
    }

    private function foreachFunc()
    {
        $alpha = [1, 2, 3];

        // @codingStandardsIgnoreStart
        foreach ($alpha as &$cam) {}
        foreach ($alpha as $cam) {}
        // @codingStandardsIgnoreEnd

        return join(' ', $alpha); // output [1, 2, 2]
    }

    private function float(): string
    {
        $a = 1;
        $b = 2.5;
        $c = 0xFF;

        $d = $b + $c;
        $e = $d * $b;
        $f = ($d + $e) % $a;

        return $f + $e;
    }

    private function closureFunc(): string
    {
        $getter = function () {
            return function () {
                return $this->foo;
            };
        };

        return $getter()->bindTo(new Bar(), Bar::class)();
    }
}
