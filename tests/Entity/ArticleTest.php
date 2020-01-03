<?php

namespace App\Tests\Entity;

use App\Entity\Article;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    public function testArticle()
    {
        $article = new Article();

        $title = "Test";
        $article->setTitle($title);
        $this->assertEquals("Test", $article->getTitle());
        // $this->assertTrue(true);
    }
}
