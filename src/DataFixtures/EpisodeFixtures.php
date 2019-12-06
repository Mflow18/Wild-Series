<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $episode = new Episode();
            $episode->setTitle($faker->word);
            $episode->setNumber($faker->numberBetween(0, 50));
            $episode->setSeason($this->getReference('saison_' . rand(0,6)));
            $episode->setSynopsis($faker->text(50));
            $manager->persist($episode);
            $this->addReference('episode_' . $i, $episode);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];

    }
}
