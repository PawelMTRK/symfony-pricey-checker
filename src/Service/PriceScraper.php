<?php

namespace App\Service;

use Facebook\WebDriver\WebDriverDimension;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Panther\Client;


class PriceScraper
{
    public function __construct(private LoggerInterface $logger)
    {
    }
    private function parse(string $whole, string $frac): float
    {
        $this->logger->info("Parsing " . $whole . ", " . $frac);
        $price = floatval(intval($whole) . "." . intval($frac));
        return $price;
    }
    public function scrape(string $url, string $selector, string $cookie_selector): float
    {
        $client = Client::createFirefoxClient();
        $client->request('GET', $url);
        $size = new WebDriverDimension(1600, 1200);
        $client->manage()->window()->setSize($size);

        // $client->takeScreenshot("INIT.png");
        $client
            ->waitForVisibility($cookie_selector)
            ->filter($cookie_selector)
            ->click();
        $price_childs = $client
            ->waitForVisibility($selector)
            ->filter($selector)
            ->children();
        $price_whole = $price_childs->eq(0)->text();
        $price_frac = $price_childs->eq(1)->text();
        // $client->takeScreenshot("POST.png");
        return $this->parse($price_whole, $price_frac);
    }
}