<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $Article = new Article();
        // $manager->persist($Article);

        $article = new Article();
        $article->setTitledesc('Widget');
        $article->setTitle('Priceless widget');
        $article->setLastupdate(\DateTime::createFromFormat('now'));
        $article->setHeading('Priceless widget');
        $article->setContent('Priceless widget');
        $manager->persist($article);

        // add more Articles

        $manager->flush();
        $manager->flush();
    }
}
