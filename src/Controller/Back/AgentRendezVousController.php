<?php

namespace App\Controller\Back;

use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\RendezVous;

#[Route('/agent/rendez-vous')]
#[IsGranted('ROLE_AGENT')]
class AgentRendezVousController extends AbstractController
{
    #[Route('/', name: 'agent_rdv_index')]
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        $user = $this->getUser();
        $banque = $user->getBanque();

        if (!$banque) {
            $this->addFlash('error', 'Vous devez être associé à une banque.');
            return $this->redirectToRoute('app_home');
        }

        $rendezVous = $rendezVousRepository->findByBanque($banque->getId());

        return $this->render('back/rendez_vous/index.html.twig', [
            'rendez_vous' => $rendezVous,
            'banque' => $banque,
        ]);
    }

    #[Route('/today', name: 'agent_rdv_today')]
    public function today(RendezVousRepository $rendezVousRepository): Response
    {
        $user = $this->getUser();
        $banque = $user->getBanque();

        if (!$banque) {
            $this->addFlash('error', 'Vous devez être associé à une banque.');
            return $this->redirectToRoute('app_home');
        }

        $rendezVous = $rendezVousRepository->findTodayByBanque($banque->getId());

        return $this->render('back/rendez_vous/today.html.twig', [
            'rendez_vous' => $rendezVous,
            'banque' => $banque,
        ]);
    }

    #[Route('/update-status/{id}', name: 'agent_rdv_update_status', methods: ['POST'])]
    public function updateStatus(
        RendezVous $rendezVous,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Ensure the appointment belongs to the agent's bank
        if ($rendezVous->getBanque() !== $this->getUser()->getBanque()) {
            throw $this->createAccessDeniedException();
        }

        $newStatut = $request->request->get('statut');
        $validStatuts = ['pending', 'confirmed', 'cancelled', 'completed'];

        if (in_array($newStatut, $validStatuts)) {
            $rendezVous->setStatut($newStatut);
            $entityManager->flush();

            $this->addFlash('success', 'Le statut du rendez-vous a été mis à jour.');
        } else {
            $this->addFlash('error', 'Statut invalide.');
        }

        return $this->redirectToRoute('agent_rdv_index');
    }
}
