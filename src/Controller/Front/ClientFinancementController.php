<?php

namespace App\Controller\Front;

use App\Entity\Financement;
use App\Repository\FinancementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/client/financement')]
#[IsGranted('ROLE_CLIENT')]
class ClientFinancementController extends AbstractController
{
    #[Route('/request', name: 'client_financement_request')]
    public function request(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $banque = $user->getBanque();

        if (!$banque) {
            $this->addFlash('error', 'Vous devez être associé à une banque pour demander un financement.');
            return $this->redirectToRoute('client_dashboard');
        }

        if ($request->isMethod('POST')) {
            $montantDemande = $request->request->get('montant_demande') ?? '0';
            $dureeMois = (int)($request->request->get('duree_mois') ?? 0);
            $objetFinancement = $request->request->get('objet_financement') ?? '';
            
            if (empty($montantDemande) || $montantDemande === '0') {
                $this->addFlash('error', 'Veuillez spécifier un montant.');
                return $this->render('front/financement/request.html.twig', ['banque' => $banque]);
            }
            
            $financement = new Financement();
            $financement->setClient($user);
            $financement->setBanque($banque);
            $financement->setMontantDemande($montantDemande);
            $financement->setDureeMois($dureeMois);
            $financement->setObjetFinancement($objetFinancement);
            $financement->setStatut('pending');

            try {
                $entityManager->persist($financement);
                $entityManager->flush();

                $this->addFlash('success', 'Votre demande de financement a été envoyée avec succès!');
                return $this->redirectToRoute('client_financement_list');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur s\'est produite: ' . $e->getMessage());
            }
        }

        return $this->render('front/financement/request.html.twig', [
            'banque' => $banque,
        ]);
    }

    #[Route('/my-requests', name: 'client_financement_list')]
    public function myRequests(FinancementRepository $financementRepository): Response
    {
        $user = $this->getUser();
        $financements = $financementRepository->findByClient($user->getId());

        return $this->render('front/financement/my_requests.html.twig', [
            'financements' => $financements,
        ]);
    }
}
