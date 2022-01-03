<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use App\Domain\Service\Component\Process;
use App\Domain\Service\Component\Property;
use App\Domain\Service\Component\Resolver;

final class ComponentManager implements ManagerInterface
{
    private $components = [];

    public function __construct(
        Process $process,
        Property $property,
        Resolver $resolver
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
                if (!preg_match('/' . ucfirst($name) . '$/', $class)) {
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
