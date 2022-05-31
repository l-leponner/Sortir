<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Participant;
use App\Entity\Place;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private ObjectManager $manager;
    private Generator $faker;
//    private UserPasswordHasherInterface $passwordHasher;


    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->hasher = $passwordHasher;
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
//        appel du hasher
        $passwordHasher = $this->hasher;

        $this->addStates();
        $this->addCampus();
        $this->addCities();
        $this->addParticipants();
        $this->addPlaces();
//        $this->addActivities();



    }

    private function addStates()
    {
        $state1 = new State();
        $state1->setWording('Activity created');
        $this->manager->persist($state1);
        $state2 = new State();
        $state2->setWording('Activity opened');
        $this->manager->persist($state2);
        $state3 = new State();
        $state3->setWording('Activity closed');
        $this->manager->persist($state3);
        $state4 = new State();
        $state4->setWording('Activity in progress');
        $this->manager->persist($state4);
        $state5 = new State();
        $state5->setWording('Activity ended');
        $this->manager->persist($state5);
        $state6 = new State();
        $state6->setWording('Activity archived');
        $this->manager->persist($state6);
        $state7 = new State();
        $state7->setWording('Activity cancelled');
        $this->manager->persist($state7);

        $this->manager->flush();
    }

    private function addCampus()
    {
        $campus1 = new Campus();
        $campus1->setName('SAINT-HERBLAIN');
        $this->manager->persist($campus1);
        $campus2 = new Campus();
        $campus2->setName('CHARTRES DE BRETAGNE');
        $this->manager->persist($campus2);
        $campus3 = new Campus();
        $campus3->setName('LA ROCHE SUR YON');
        $this->manager->persist($campus3);
        $campus4 = new Campus();
        $campus4->setName('QUIMPER');
        $this->manager->persist($campus4);

        $this->manager->flush();
    }

    private function addCities()
    {
        $city1 = new City();
        $city1->setName('Quimper')->setPostCode('29000');
        $this->manager->persist($city1);
        $city2 = new City();
        $city2->setName('Rennes')->setPostCode('35000');
        $this->manager->persist($city2);
        $city3 = new City();
        $city3->setName('Nantes')->setPostCode('44000');
        $this->manager->persist($city3);
        $city4 = new City();
        $city4->setName('La Roche sur Yon')->setPostCode('85000');
        $this->manager->persist($city4);

        $this->manager->flush();
    }

    private function addParticipants()
    {
        $campusRepo = $this->manager->getRepository(Campus::class);
        $campuses = $campusRepo->findAll();

        $passwordHasher = $this->hasher;

        $participant1 = new Participant();
        $p1pwd = 'p1pwd';
        $participant1->setName($this->faker->lastName())
            ->setFirstname($this->faker->firstName())
            ->setPhone($this->faker->phoneNumber())
            ->setActive(true)
            ->setEmail($this->faker->email())
            ->setCampus($this->faker->randomElement($campuses))
            ->setUsername($this->faker->userName())
            ->setPassword(
                $this->hasher->hashPassword(
                    $participant1,
                    $p1pwd
                )
            );
        $this->manager->persist($participant1);

        $participant2 = new Participant();
        $p2pwd = 'p2pwd';
        $participant2->setName($this->faker->lastName())
            ->setFirstname($this->faker->firstName())
            ->setPhone($this->faker->phoneNumber())
            ->setActive(true)
            ->setEmail($this->faker->email())
            ->setCampus($this->faker->randomElement($campuses))
            ->setUsername($this->faker->userName())
            ->setPassword(
                $this->hasher->hashPassword(
                    $participant2,
                    $p2pwd
                )
            );
        $this->manager->persist($participant2);

        $participantLaurent = new Participant();
        $laurentpwd = 'laurentpwd';
        $participantLaurent->setName('Le Ponner')
            ->setFirstname('Laurent')
            ->setPhone('0605040302')
            ->setActive(true)
            ->setEmail('laurent@mail.com')
            ->setCampus($this->faker->randomElement($campuses))
            ->setUsername('LaurentLP')
            ->setPassword(
                $this->hasher->hashPassword(
                    $participantLaurent,
                    $laurentpwd
                )
            );

        $this->manager->persist($participantLaurent);

        $this->manager->flush();

        for ($i=1; $i<20; $i++){
            $participant = new Participant();
            $ppwd = 'ppwd';
            $participant->setName($this->faker->lastName())
                ->setFirstname($this->faker->firstName())
                ->setPhone($this->faker->phoneNumber())
                ->setActive(true)
                ->setEmail($this->faker->email())
                ->setCampus($this->faker->randomElement($campuses))
                ->setUsername($this->faker->userName())
                ->setPassword(
                    $this->hasher->hashPassword(
                        $participant,
                        $ppwd
                    )
                )
            ;

            $this->manager->persist($participant);

            $this->manager->flush();
        }
    }

    private function addPlaces()
    {
        $placeNames = ['Golf', 'Bar', 'Chez Bibi', 'Fête foraine', 'Plage', 'Karting', 'Cinema'];
        $cityRepo = $this->manager->getRepository(City::class);
        $cities = $cityRepo->findAll();

        for ($i=1; $i<20; $i++){
            $place = new Place();
            $place->setName($this->faker->randomElement($placeNames))
                ->setCity($this->faker->randomElement($cities))
                ->setLatitude($this->faker->latitude())
                ->setLongitude($this->faker->longitude())
                ->setStreet($this->faker->streetName())
            ;
            $this->manager->persist($place);
    }
        $this->manager->flush();

    }

//    private function addActivities()
//    {
//        $activitiesNames = ['Billard', 'Course de karting', 'Nouveau film au cinéma', 'Baignade', 'BBQ'];
//        $campusRepo = $this->manager->getRepository(Campus::class);
//        $campuses = $campusRepo->findAll();
//
//
//        $activity = new Activity();
//        $activity->setName($this->faker->randomElement($activitiesNames))
//            ->setCampus($this->faker->randomElement($campuses))
//        ->setDateTimeBeginning($this->faker->dateTimeBetween('-6 month', 'now'))
//        ->setDuration($this->faker->time('H:i:s', new \DateTime()));
//
//    }


}
