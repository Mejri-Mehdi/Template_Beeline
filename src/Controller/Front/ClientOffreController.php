<?php

namespace App\Controller\Front;

use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/client/offres')]
#[IsGranted('ROLE_CLIENT')]
class ClientOffreController extends AbstractController
{
    #[Route('/', name: 'client_offres')]
    public function index(OffreRepository $offreRepository): Response
    {
        $user = $this->getUser();
        $banque = $user->getBanque();

        if (!$banque) {
            $this->addFlash('warning', 'Vous devez être associé à une banque pour voir les offres.');
            return $this->redirectToRoute('client_dashboard');
        }

        $offres = $offreRepository->findActiveByBanque($banque->getId());

        return $this->render('front/offre/index.html.twig', [
            'offres' => $offres,
            'banque' => $banque,
        ]);
    }
}
