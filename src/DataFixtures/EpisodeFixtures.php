<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{ 
    public function load(ObjectManager $manager): void
    {
        //Puis ici nous demandons à la Factory de nous fournir un Faker
        $faker = Factory::create();

        /**
        * L'objet $faker que tu récupère est l'outil qui va te permettre 
        * de te générer toutes les données que tu souhaites
        */
        for($p = 0; $p <= 7; $p++){
            for($s = 1; $s <= 5; $s++) {
                for($e = 1; $e <= 10; $e++) {
                    $episode = new Episode();
                    $episode->setTitle($faker->title());
                    $episode->setNumber($e);
                    $episode->setSynopsis($faker->paragraphs(3, true));
                    $episode->setSeason($this->getReference('program_' . $p . '_season_' . $s));

                    $manager->persist($episode);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}