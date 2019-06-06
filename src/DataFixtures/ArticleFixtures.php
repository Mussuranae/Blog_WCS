<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++)
        {
            $article = new Article();
            $article->setTitle(mb_strtolower($faker->word()));
            $article->setContent($faker->text($maxNbChars = 200));
            $article->setCategory($this->getReference('categorie_' . rand(0, 4)));
            $article->setSlug();
            $manager->persist($article);
        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
}