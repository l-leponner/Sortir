<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Participant;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->hasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
//        appel de la factory faker
        $faker = Factory::create('fr_FR');

//        appel du hasher
        $passwordHasher = $this->hasher;

        $state1 = new State();
        $state1->setWording('Activity created');
        $manager->persist($state1);
        $state2 = new State();
        $state2->setWording('Activity opened');
        $manager->persist($state2);
        $state3 = new State();
        $state3->setWording('Activity closed');
        $manager->persist($state3);
        $state4 = new State();
        $state4->setWording('Activity in progress');
        $manager->persist($state4);
        $state5 = new State();
        $state5->setWording('Activity ended');
        $manager->persist($state5);
        $state6 = new State();
        $state6->setWording('Activity archived');
        $manager->persist($state6);
        $state7 = new State();
        $state7->setWording('Activity cancelled');
        $manager->persist($state7);

        $campus1 = new Campus();
        $campus1->setName('SAINT-HERBLAIN');
        $manager->persist($campus1);
        $campus2 = new Campus();
        $campus2->setName('CHARTRES DE BRETAGNE');
        $manager->persist($campus2);
        $campus3 = new Campus();
        $campus3->setName('LA ROCHE SUR YON');
        $manager->persist($campus3);
        $campus4 = new Campus();
        $campus4->setName('QUIMPER');
        $manager->persist($campus4);

        $city1 = new City();
        $city1->setName('Quimper')->setPostCode('29000');
        $manager->persist($city1);
        $city2 = new City();
        $city2->setName('Rennes')->setPostCode('35000');
        $manager->persist($city2);
        $city3 = new City();
        $city3->setName('Nantes')->setPostCode('44000');
        $manager->persist($city3);
        $city4 = new City();
        $city4->setName('La Roche sur Yon')->setPostCode('85000');
        $manager->persist($city4);

        $participant1 = new Participant();
        $p1pwd = 'p1pwd';
        $participant1->setName($faker->lastName())
            ->setFirstname($faker->firstName())
            ->setPhone($faker->phoneNumber())
            ->setActive(true)
            ->setEmail($faker->email())
            ->setCampus($campus1)
            ->setPassword(
                $passwordHasher->hashPassword(
                    $participant1,
                    $p1pwd
                )
            );
        $manager->persist($participant1);


        $manager->flush();
    }
}
