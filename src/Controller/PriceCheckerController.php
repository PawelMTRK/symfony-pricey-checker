<?php

namespace App\Controller;

use App\Entity\Price;
use App\Service\PriceScraper;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Item;

final class PriceCheckerController extends AbstractController
{
    #[Route('/', 'price_checker_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $items = $em->getRepository(Item::class)->findAll();
        return $this->render('price_checker/index.html.twig', [
            'controller_name' => 'PriceCheckerController',
            'items' => $items
        ]);
    }
    #[Route('/check', 'price_checker_check')]
    public function checkPrices(EntityManagerInterface $em, PriceScraper $ps): Response
    {
        $items = $em->getRepository(Item::class)->findBy(['store' => 1]);
        $p = new Price();
        foreach ($items as $item) {
            $value = $ps->scrape($item->getUrl(), $item->getStore()->getSelector());
            $p->setItem($item);
            $p->setValue($value);
            $p->setCheckedAt(new DateTimeImmutable("now"));
            $em->persist($p);
        }
        $em->flush();
        return $this->redirectToRoute('price_checker_index');
    }
}
