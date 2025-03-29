<?php

namespace App\Service;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Panther\Client;


class PriceScraper
{
    public function __construct(private LoggerInterface $logger) {}
    private function parse(string $pricetext) : float
    {
        $this->logger->info("Parsowanie ceny '" . $pricetext . "'");
        $pricetext = preg_replace('/[^0-9\.]/', '', $pricetext);
        if (!str_contains($pricetext, ".")) {
            $p = strlen($pricetext) - 2;
            $pricetext = substr($pricetext, 0, $p) . "." . substr($pricetext, $p);
        }
        $price = floatval($pricetext);
        return $price;
    }
    public function scrape(string $url, string $selector): float
    {
        $client = Client::createFirefoxClient();
        $client->request('GET', $url);
        $client->takeScreenshot("TEST.png");
        try {
            // TODO add field to Store entity that has cookie button text
            $client->clickLink("ZAAKCEPTUJ WSZYSTKIE");
        } catch(InvalidArgumentException) {

        }
        $crawler = $client->waitFor($selector);
        $text = $crawler->filter($selector)->text(null, true);
        return $this->parse($text);
    }
}