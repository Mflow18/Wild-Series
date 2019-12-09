<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'Action',
        'Aventure',
        "Drama",
        "Science Fiction",
        'Horreur',
    ];

    public function load(ObjectManager $manager)
    {

        foreach (SELF::CATEGORIES as $key => $categoryName) {
            $category = new Category();
            $category->setName($categoryName);

            $manager->persist($category);
            $this->addReference('categorie_' . $key, $category);
        }

        $manager->flush();
    }
}