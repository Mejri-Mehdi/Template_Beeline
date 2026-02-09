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
}
