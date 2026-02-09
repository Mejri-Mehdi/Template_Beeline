<?php

namespace App\Controller\Front;

use App\Repository\RendezVousRepository;
use App\Repository\FinancementRepository;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/client')]
#[IsGranted('ROLE_CLIENT')]
class ClientDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'client_dashboard')]
    public function dashboard(
        RendezVousRepository $rendezVousRepository,
        FinancementRepository $financementRepository,
        OffreRepository $offreRepository
    ): Response {
        $user = $this->getUser();
        
        // Get client statistics
        $totalRdv = count($rendezVousRepository->findByClient($user->getId()));
        $upcomingRdv = count($rendezVousRepository->findUpcomingByClient($user->getId()));
        $totalFinancements = count($financementRepository->findByClient($user->getId()));
        
        // Get active offers from client's bank
        $activeOffres = $user->getBanque() 
            ? $offreRepository->findActiveByBanque($user->getBanque()->getId())
            : [];

        return $this->render('front/client/dashboard.html.twig', [
            'user' => $user,
            'stats' => [
                'total_rdv' => $totalRdv,
                'upcoming_rdv' => $upcomingRdv,
                'total_financements' => $totalFinancements,
                'active_offres' => count($activeOffres),
            ],
            'recent_rdv' => array_slice($rendezVousRepository->findByClient($user->getId()), 0, 5),
            'active_offres' => array_slice($activeOffres, 0, 3),
        ]);
    }
}
