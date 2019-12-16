<?php

namespace App\DataFixtures;


use App\Entity\Actor;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name);
            $slugify = new Slugify();
            $slug = $slugify->generate($actor->getName());
            $actor->setSlug($slug);
            $actor->addProgram($this->getReference('program_' . rand(1, count(ProgramFixtures::PROGRAMS))));

            $manager->persist($actor);
            $this->addReference('acteur_' . $i, $actor);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];

    }

}
