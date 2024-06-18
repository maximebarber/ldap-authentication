<?php

namespace App\Controller;

use App\Repository\CardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CardStackController extends AbstractController
{
    #[Route('/card-stack', name: 'app_card_stack')]
    public function index(CardRepository $cardRepository): Response
    {
        $user = $this->getUser();
        $cards = $cardRepository->findAll();
        dd($user->getEntry()->getAttribute('cn')[0]);
        return $this->render('card_stack/index.html.twig', [
            'controller_name' => 'CardStackController',
            'cards' => $cards,
            'user' => $user,
        ]);
    }
}
