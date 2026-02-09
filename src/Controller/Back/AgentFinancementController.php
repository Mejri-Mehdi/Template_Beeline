<?php

namespace App\Controller\Back;

use App\Repository\FinancementRepository;
use App\Entity\Financement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/agent/financement')]
#[IsGranted('ROLE_AGENT')]
class AgentFinancementController extends AbstractController
{
    #[Route('/', name: 'agent_financement_index')]
    public function index(FinancementRepository $financementRepository): Response
    {
        $user = $this->getUser();
        $banque = $user->getBanque();

        if (!$banque) {
            $this->addFlash('error', 'Vous devez être associé à une banque.');
            return $this->redirectToRoute('app_home');
        }

        $financements = $financementRepository->findByBanque($banque->getId());

        return $this->render('back/financement/index.html.twig', [
            'financements' => $financements,
            'banque' => $banque,
        ]);
    }

    #[Route('/approve/{id}', name: 'agent_financement_approve', methods: ['POST'])]
    public function approve(
        Financement $financement,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Ensure the financing belongs to the agent's bank
        if ($financement->getBanque() !== $this->getUser()->getBanque()) {
            throw $this->createAccessDeniedException();
        }

        $financement->setStatut('approved');
        $financement->setCommentaireAgent($request->request->get('commentaire', 'Demande approuvée'));
        
        $entityManager->flush();

        $this->addFlash('success', 'La demande de financement a été approuvée.');
        return $this->redirectToRoute('agent_financement_index');
    }

    #[Route('/reject/{id}', name: 'agent_financement_reject', methods: ['POST'])]
    public function reject(
        Financement $financement,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Ensure the financing belongs to the agent's bank
        if ($financement->getBanque() !== $this->getUser()->getBanque()) {
            throw $this->createAccessDeniedException();
        }

        $financement->setStatut('rejected');
        $financement->setCommentaireAgent($request->request->get('commentaire', 'Demande rejetée'));
        
        $entityManager->flush();

        $this->addFlash('success', 'La demande de financement a été rejetée.');
        return $this->redirectToRoute('agent_financement_index');
    }
}
