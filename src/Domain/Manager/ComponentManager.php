<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use App\Domain\Service\Component\ProcessComp;
use App\Domain\Service\Component\PropertyComp;
use App\Domain\Service\Component\ResolverComp;

final class ComponentManager implements ManagerInterface
{
    private $components = [];

    public function __construct(
        ProcessComp $process,
        PropertyComp $property,
        ResolverComp $resolver
    ) {
        foreach (func_get_args() as $component) {
            $this->components[\get_class($component)] = $component;
        }
    }

    public function call(array $context = []): array
    {
        $data = [];

        $name = $context['name'] ?? null;
        foreach ($this->components as $class => $component) {
            if ($name) {
                if (!preg_match('/' . $name . '$/', strtolower($class))) {
                    continue;
                } else {
                    $data = $component->use();
                }
            } else {
                $data[$class] = $component->use();
            }
        }

        return $data;
    }
}
