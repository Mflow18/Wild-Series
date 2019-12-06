<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 8; $i++) {
            $season = new Season();
            $season->setYear($faker->year);
            $season->setDescription($faker->text(50));
            $season->setProgram($this->getReference('program_' . rand(0,count(ProgramFixtures::PROGRAMS) - 1)));
            $manager->persist($season);
            $this->addReference('saison_' . $i, $season);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
        return [EpisodeFixtures::class];

    }

}
