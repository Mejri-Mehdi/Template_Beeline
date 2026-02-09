<?php

namespace App\Controller\Admin;

use App\Repository\UtilisateurRepository;
use App\Repository\BanqueRepository;
use App\Repository\RendezVousRepository;
use App\Repository\FinancementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function dashboard(
        UtilisateurRepository $utilisateurRepository,
        BanqueRepository $banqueRepository,
        RendezVousRepository $rendezVousRepository,
        FinancementRepository $financementRepository
    ): Response {
        $users = $utilisateurRepository->findAll();
        $banques = $banqueRepository->findAll();

        // Statistics
        $stats = [
            'total_users' => count($users),
            'total_clients' => count($utilisateurRepository->findByRole('ROLE_CLIENT')),
            'total_agents' => count($utilisateurRepository->findByRole('ROLE_AGENT')),
            'pending_users' => count($utilisateurRepository->findPendingUsers()),
            'total_banques' => count($banques),
            'active_banques' => count($banqueRepository->findActiveBanques()),
            'pending_banques' => count($banqueRepository->findPendingBanques()),
        ];

        // Get recent activity
        $recentUsers = array_slice($utilisateurRepository->findBy([], ['id' => 'DESC']), 0, 5);
        $pendingUsers = $utilisateurRepository->findPendingUsers();
        $pendingBanques = $banqueRepository->findPendingBanques();

        return $this->render('admin/dashboard.html.twig', [
            'stats' => $stats,
            'recent_users' => $recentUsers,
            'pending_users' => $pendingUsers,
            'pending_banques' => $pendingBanques,
        ]);
    }
}
