<?php

namespace App\Controller\Back;

use App\Repository\RendezVousRepository;
use App\Repository\FinancementRepository;
use App\Repository\BanqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/agent')]
#[IsGranted('ROLE_AGENT')]
class AgentController extends AbstractController
{
    #[Route('/dashboard', name: 'agent_dashboard')]
    public function dashboard(
        RendezVousRepository $rendezVousRepository,
        FinancementRepository $financementRepository,
        BanqueRepository $banqueRepository
    ): Response {
        $user = $this->getUser();
        $banque = $user->getBanque();

        if (!$banque) {
            $this->addFlash('error', 'Vous devez être associé à une banque.');
            return $this->redirectToRoute('app_home');
        }

        // Get agent statistics for their bank
        $stats = $banqueRepository->getBanqueStatistics($banque->getId());
        
        // Get today's appointments
        $todayRdv = $rendezVousRepository->findTodayByBanque($banque->getId());
        
        // Get pending financing requests
        $pendingFinancements = $financementRepository->findPendingByBanque($banque->getId());
        
        // Additional stats
        $stats['pending_rdv'] = $rendezVousRepository->countByStatutAndBanque('pending', $banque->getId());
        $stats['pending_financements'] = count($pendingFinancements);
        $stats['today_rdv'] = count($todayRdv);

        return $this->render('back/agent/dashboard.html.twig', [
            'user' => $user,
            'stats' => $stats,
            'today_rdv' => $todayRdv,
            'pending_financements' => array_slice($pendingFinancements, 0, 5),
            'banque' => $banque,
        ]);
    }
}
