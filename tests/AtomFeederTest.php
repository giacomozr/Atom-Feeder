<?php

use PHPUnit\Framework\TestCase;
use App\AtomFeeder;

final class AtomFeederTest extends TestCase
{
    public function testCanGenerateFeed() {
        $atomFeeder = new AtomFeeder('https://www.internazionale.it/i-piu-letti', '.box-article-title');
        $atomFeed = $atomFeeder->generateFeed();

        $this->assertNotEmpty($atomFeed);
    }

    public function testCannotFetchArticles() {
        $this->expectExceptionMessage('Cannot fetch articles with query selector .nothing');

        new AtomFeeder('https://www.internazionale.it/i-piu-letti', '.nothing');
    }
}