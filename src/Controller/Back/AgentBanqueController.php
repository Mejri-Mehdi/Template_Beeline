<?php

namespace App\Controller\Back;

use App\Entity\Banque;
use App\Entity\Agence;
use App\Repository\BanqueRepository;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/agent/banque')]
#[IsGranted('ROLE_AGENT')]
class AgentBanqueController extends AbstractController
{
    #[Route('/', name: 'agent_banque_view')]
    public function view(AgenceRepository $agenceRepository): Response
    {
        $user = $this->getUser();
        $banque = $user->getBanque();

        if (!$banque) {
            $this->addFlash('error', 'Vous devez être associé à une banque.');
            return $this->redirectToRoute('app_home');
        }

        $agences = $agenceRepository->findByBanque($banque->getId());

        return $this->render('back/banque/view.html.twig', [
            'banque' => $banque,
            'agences' => $agences,
        ]);
    }

    #[Route('/edit', name: 'agent_banque_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $banque = $user->getBanque();

        if (!$banque) {
            $this->addFlash('error', 'Vous devez être associé à une banque.');
            return $this->redirectToRoute('app_home');
        }

        if ($request->isMethod('POST')) {
            $banque->setNomBq($request->request->get('nom_bq'));
            $banque->setSiteWeb($request->request->get('site_web'));
            $banque->setTelephoneBq($request->request->get('telephone_bq'));
            $banque->setEmailBq($request->request->get('email_bq'));
            $banque->setDescription($request->request->get('description'));

            try {
                $entityManager->flush();
                $this->addFlash('success', 'Les informations de la banque ont été mises à jour.');
                return $this->redirectToRoute('agent_banque_view');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur s\'est produite: ' . $e->getMessage());
            }
        }

        return $this->render('back/banque/edit.html.twig', [
            'banque' => $banque,
        ]);
    }

    #[Route('/agence/new', name: 'agent_agence_new')]
    public function newAgence(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $banque = $user->getBanque();

        if (!$banque) {
            $this->addFlash('error', 'Vous devez être associé à une banque.');
            return $this->redirectToRoute('app_home');
        }

        if ($request->isMethod('POST')) {
            $agence = new Agence();
            $agence->setBanque($banque);
            $agence->setNomAg($request->request->get('nom_ag'));
            $agence->setAdresseAg($request->request->get('adresse_ag'));
            $agence->setTelephone($request->request->get('telephone'));
            $agence->setEmail($request->request->get('email'));
            $agence->setHoraires($request->request->get('horaires'));

            try {
                $entityManager->persist($agence);
                $entityManager->flush();

                $this->addFlash('success', 'L\'agence a été créée avec succès.');
                return $this->redirectToRoute('agent_banque_view');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur s\'est produite: ' . $e->getMessage());
            }
        }

        return $this->render('back/agence/new.html.twig', [
            'banque' => $banque,
        ]);
    }

    #[Route('/agence/edit/{id}', name: 'agent_agence_edit')]
    public function editAgence(Agence $agence, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Ensure the agency belongs to the agent's bank
        if ($agence->getBanque() !== $this->getUser()->getBanque()) {
            throw $this->createAccessDeniedException();
        }

        if ($request->isMethod('POST')) {
            $agence->setNomAg($request->request->get('nom_ag'));
            $agence->setAdresseAg($request->request->get('adresse_ag'));
            $agence->setTelephone($request->request->get('telephone'));
            $agence->setEmail($request->request->get('email'));
            $agence->setHoraires($request->request->get('horaires'));

            try {
                $entityManager->flush();
                $this->addFlash('success', 'L\'agence a été modifiée avec succès.');
                return $this->redirectToRoute('agent_banque_view');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur s\'est produite: ' . $e->getMessage());
            }
        }

        return $this->render('back/agence/edit.html.twig', [
            'agence' => $agence,
        ]);
    }

    #[Route('/agence/delete/{id}', name: 'agent_agence_delete', methods: ['POST'])]
    public function deleteAgence(Agence $agence, EntityManagerInterface $entityManager): Response
    {
        // Ensure the agency belongs to the agent's bank
        if ($agence->getBanque() !== $this->getUser()->getBanque()) {
            throw $this->createAccessDeniedException();
        }

        try {
            $entityManager->remove($agence);
            $entityManager->flush();
            $this->addFlash('success', 'L\'agence a été supprimée avec succès.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Impossible de supprimer cette agence: ' . $e->getMessage());
        }

        return $this->redirectToRoute('agent_banque_view');
    }
}
