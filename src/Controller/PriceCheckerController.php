<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Item;

final class PriceCheckerController extends AbstractController
{
    #[Route('/', name: 'app_price_checker')]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {
        $items = $entityManagerInterface->getRepository(Item::class)->findAll();
        return $this->render('price_checker/index.html.twig', [
            'controller_name' => 'PriceCheckerController',
            'items' => $items
        ]);
    }
}
