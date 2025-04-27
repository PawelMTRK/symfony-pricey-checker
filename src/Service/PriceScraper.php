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
    // combining intval and str_replace wouldn't work without many edge-cases
    // just parse str char by char and return only numbers
    private function cleanup(string $str): string
    {
        $tmp = "";
        foreach (str_split($str) as $char) {
            if (is_numeric($char)) {
                $tmp = $tmp . $char;
            }
        }
        return $tmp;
    }
    private function parse(string $whole, string $frac): float
    {
        $this->logger->info("Parsing " . $whole . ", " . $frac);
        $price_text =
            $this->cleanup($whole) . 
            "." . 
            $this->cleanup($frac);
        $price = floatval($price_text);
        $this->logger->info("Result " . $price);
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
        $price_whole = $price_childs->eq(0)->text(normalizeWhitespace: true);
        $price_frac = $price_childs->eq(1)->text(normalizeWhitespace: true);
        // $client->takeScreenshot("POST.png");
        return $this->parse($price_whole, $price_frac);
    }
}