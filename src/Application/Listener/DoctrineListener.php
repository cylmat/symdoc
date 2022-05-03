<?php

namespace App\Application\Listener;

use App\Domain\Entity\Product;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/events.html#lifecycle-events
 */
class DoctrineListener
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $args->getObject();
        $args->getObjectManager();
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Product) {
            return;
        }

        $entity->date = (new \DateTime())->format('Y-m-d');

        //$entityManager = $args->getObjectManager();
        // ...
    }
}
