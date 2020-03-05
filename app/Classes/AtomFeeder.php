<?php
namespace App;

use Graby\Graby;
use PHPHtmlParser\Dom;
use \FeedWriter\ATOM;

class AtomFeeder {
    private $url;
    private $parsedUrl;
    private $queryElements;
    private $dom;
    private $rssFeed;

    public function __construct($url, $queryElements = "article h1 a") {
        $this->setUrl($url);
        $this->setQueryElements($queryElements);
        $this->initRssFeed();
        $this->getDom();
        $this->fetchArticles();
    }

    public function setUrl($url) {
        if (!isset($url)) {
            throw new \Exception('Missing url parameter');
        }

        $this->url = $url;
        $this->parsedUrl = parse_url($this->url);
    }

    public function setQueryElements($elements) {
        $this->queryElements = $elements;
    }

    private function initRssFeed() {
        $this->rssFeed = new ATOM();
    }

    private function getDom() {
        $this->dom = new Dom;
        $this->dom->setOptions([
            'removeScripts' => true
        ]);
        try {
            $this->dom->loadFromUrl($this->url);
        } catch (\Exception $exception) {
            throw new \Exception('Cannot fetch articles from ' . $this->url);
        }

        $this->rssFeed->setTitle($this->dom->find('title')->innerHtml);
        $this->rssFeed->setDescription($this->dom->find('meta[name="description"]')->getAttribute('content'));
        $this->rssFeed->setLink($this->url);
        $this->rssFeed->setDate(new \DateTime());
    }

    private function fetchArticles() {
        $articlesLink = $this->dom->find($this->queryElements);

        if (count($articlesLink) === 0) {
            throw new \Exception('Cannot fetch articles with query selector ' . $this->queryElements);
        }

        foreach ($articlesLink as $link) {
            $graby = new Graby();

            $articleUrl = $link->getAttribute('href');

            if (!$articleUrl) {
                continue;
            }

            if (!!preg_match('/^\//', $articleUrl)) {
                $articleUrl = $this->parsedUrl['scheme'] . '://' . $this->parsedUrl['host'] . $articleUrl;
            }

            $articleData = $graby->fetchContent($articleUrl);

            // Create new feed item
            $articleItem = $this->rssFeed->createNewItem();

            // Set date
            $date = new \DateTime($articleData['date']);
            $articleItem->setDate($date);

            // Set title
            $articleItem->setTitle($articleData['title']);

            // Set link
            $articleItem->setLink($articleData['url']);

            // Set author
            $articleItem->setAuthor($articleData['authors'] ? $articleData['authors'][0] : $articleData['authors']);

            // Set description
            $articleItem->setDescription($articleData['summary']);
            $articleItem->setContent($articleData['html']);

            // Add item to RSS feed
            $this->rssFeed->addItem($articleItem);
        }
    }

    public function generateFeed() {
        return $this->rssFeed->generateFeed();
    }
}