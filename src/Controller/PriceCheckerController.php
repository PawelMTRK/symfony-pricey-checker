<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PriceCheckerController extends AbstractController
{
    #[Route('/', name: 'app_price_checker')]
    public function index(): Response
    {
        return $this->render('price_checker/index.html.twig', [
            'controller_name' => 'PriceCheckerController',
        ]);
    }
}
