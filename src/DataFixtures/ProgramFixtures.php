<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Program;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{   
    const PROGRAMS = [['title' => 'Walking dead', 'synopsis' => 'Des zombies envahissent la terre', 'category' => 'category_Action'],
                      ['title' => 'Doctor who', 'synopsis' => 'le Docteur parcourt l\'espace et le temps dans son TARDIS', 'category' => 'category_Aventure'],
                      ['title' => 'Tokyo Revengers', 'synopsis' => 'Takemichi Hanagaki n\'a pas vraiment réussi sa vie', 'category' => 'category_Animation'],
                      ['title' => 'Outlander', 'synopsis' => 'À la fin des années 1940, durant sa seconde lune de miel, Claire, une ancienne infirmière de guerre, se retrouve soudain propulsée au XVIIIe siècle', 'category' => 'category_Fantastique'],
                      ['title' => 'Hannibal', 'synopsis' => 'La relation étrange entre le célèbre psychiatre Hannibal Lecter et l\'un de ses patients', 'category' => 'category_Horreur'],
    ];
    public function load(ObjectManager $manager): void
    {   
        foreach(self::PROGRAMS as $key => $pgm) {
            $program = new Program();
            $program->setTitle($pgm['title'])
                    ->setSynopsis($pgm['synopsis'])
                    ->setCategory($this->getReference($pgm['category']));
            $manager->persist($program);            
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            CategoryFixtures::class,
        ];
    }
}
