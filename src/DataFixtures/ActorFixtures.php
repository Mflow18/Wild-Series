<?php

namespace App\DataFixtures;


use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = [
        'Liam Neeson',
        'Ricardo Milos',
        'Sarah Michelle Gellar',
        'Roberto Malone',
        'Michael Jackson'
    ];

    public function load(ObjectManager $manager)
    {
        foreach (SELF::ACTORS as $key => $actorName) {
            $actor = new Actor();
            $actor->setName($actorName);

            $manager->persist($actor);
            $this->addReference('acteur_' . $key, $actor);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];

    }

}
