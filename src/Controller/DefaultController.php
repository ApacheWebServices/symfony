<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use FeedIo\Factory;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{

    private $feedUrl;

    public function __construct($url = 'http://feeds.soundcloud.com/users/soundcloud:users:269860783/sounds.rss')
    {
        $this->feedUrl = $url;
    }

    public function index(LoggerInterface $logger)
    {
        $articles = [];
        try {
            $feedIo = Factory::create()->getFeedIo();
            $articles = $feedIo->read($this->feedUrl)->getFeed();
            $logger->info('feed count ' . count($articles));
            return $this->render('rss/index.html.twig', array('articles' => $articles));
        } catch (\Exception $e) {
            $logger->error($e->getMessage());
            return $this->render('rss/index.html.twig', array('articles' => []));
        }
    }
}
