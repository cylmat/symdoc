<?php

namespace App\Domain\DataFixtures;

use App\Domain\Entity\Sport;
use App\Domain\Entity\Token;
use App\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $volley = (new Sport())
            ->setName('Volley');
        $basket = (new Sport())
            ->setName('Basket');
        $roller = (new Sport())
            ->setName('Roller');

        $tokena = (new Token())->setValue(uniqId());
        $amanda = (new User())
            ->setUsername('Amanda')
            ->setToken($tokena)
            ->addSport($volley)
            ->addSport($basket);

        $tokenj = (new Token())->setValue(uniqId());
        $john = (new User())
            ->setUsername('John')
            ->setToken($tokenj)
            ->addSport($roller);

        $manager->persist($tokena);
        $manager->persist($tokenj);
        $manager->persist($amanda);
        $manager->persist($john);
        $manager->flush();
    }
}
