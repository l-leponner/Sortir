<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $participant2 = new Participant();
        $p2pwd = 'p2pwd';
        $participant2->setName($faker->lastName())
            ->setFirstname($faker->firstName())
            ->setPhone($faker->phoneNumber())
            ->setActive(true)
            ->setEmail($faker->email())
            ->setCampus($campus1)
            ->setPassword(
                $passwordHasher->hashPassword(
                    $participant2,
                    $p2pwd
                )
            );
        $manager->persist($participant2);

        $manager->flush();
    }
}
