<?php

namespace App\Controller\Front;

use App\Repository\AgenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/client/banque')]
#[IsGranted('ROLE_CLIENT')]
class ClientBanqueController extends AbstractController
{
    #[Route('/', name: 'client_banque')]
    public function view(AgenceRepository $agenceRepository): Response
    {
        $user = $this->getUser();
        $banque = $user->getBanque();

        if (!$banque) {
            $this->addFlash('warning', 'Vous n\'êtes pas encore associé à une banque.');
            return $this->redirectToRoute('client_dashboard');
        }

        $agences = $agenceRepository->findByBanque($banque->getId());

        return $this->render('front/banque/view.html.twig', [
            'banque' => $banque,
            'agences' => $agences,
        ]);
    }

    #[Route('/autres', name: 'client_autres_banques')]
    public function autresBanques(\App\Repository\BanqueRepository $banqueRepository): Response
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
