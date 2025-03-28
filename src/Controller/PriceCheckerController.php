<?php

namespace App\Controller;

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
    public function checkPrices(EntityManagerInterface $em): Response
    {
        return $this->redirectToRoute('price_checker_index');
    }
}
