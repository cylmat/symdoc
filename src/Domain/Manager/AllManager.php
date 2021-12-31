<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;

final class AllManager implements ManagerInterface
{
    public function call(): array
    {
        return [
            'foreach' => $this->foreachFunc()
        ];
    }

    public function foreachFunc()
    {
        $alpha = [1, 2, 3];

        // @codingStandardsIgnoreStart
        foreach ($alpha as &$cam) {}
        foreach ($alpha as $cam) {}
        // @codingStandardsIgnoreEnd

        return ($alpha); // output [1, 2, 2]
    }
}
