<?php

namespace App\Controller\Front;

use App\Repository\BanqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/client/autres-banques')]
#[IsGranted('ROLE_CLIENT')]
class ClientAutresBanquesController extends AbstractController
{
    #[Route('/', name: 'client_autres_banques')]
    public function index(BanqueRepository $banqueRepository): Response
    {
        $user = $this->getUser();
        $userBanque = $user->getBanque();
        
        // Get all banks except user's own bank
        $allBanques = $banqueRepository->findAll();
        $autresBanques = array_filter($allBanques, function($banque) use ($userBanque) {
            return !$userBanque || $banque->getId() !== $userBanque->getId();
        });

        return $this->render('front/banque/autres.html.twig', [
            'banques' => $autresBanques,
            'userBanque' => $userBanque,
        ]);
    }
}
