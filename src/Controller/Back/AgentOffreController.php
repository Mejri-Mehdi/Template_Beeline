<?php

namespace App\Controller\Back;

use App\Entity\Offre;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/agent/offre')]
#[IsGranted('ROLE_AGENT')]
class AgentOffreController extends AbstractController
{
    #[Route('/', name: 'agent_offre_index')]
    public function index(OffreRepository $offreRepository): Response
    {
        $user = $this->getUser();
        $banque = $user->getBanque();

        if (!$banque) {
            $this->addFlash('error', 'Vous devez être associé à une banque.');
            return $this->redirectToRoute('app_home');
        }

        $offres = $offreRepository->findByBanque($banque->getId());

        return $this->render('back/agent/offre/index.html.twig', [
            'offres' => $offres,
            'banque' => $banque,
        ]);
    }

    #[Route('/new', name: 'agent_offre_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $banque = $user->getBanque();

        if (!$banque) {
            $this->addFlash('error', 'Vous devez être associé à une banque.');
            return $this->redirectToRoute('app_home');
        }

        if ($request->isMethod('POST')) {
            $offre = new Offre();
            $offre->setBanque($banque);
            $offre->setTitre($request->request->get('titre'));
            $offre->setDescription($request->request->get('description'));
            $offre->setTypeOffre($request->request->get('type_offre'));
            $offre->setConditions($request->request->get('conditions'));
            $offre->setTaux($request->request->get('taux'));
            $offre->setActive($request->request->get('active') === '1');

            // Dates
            $dateDebut = $request->request->get('date_debut');
            if ($dateDebut) {
                $offre->setDateDebut(\DateTime::createFromFormat('Y-m-d', $dateDebut));
            }
            
            $dateFin = $request->request->get('date_fin');
            if ($dateFin) {
                $offre->setDateFin(\DateTime::createFromFormat('Y-m-d', $dateFin));
            }

            try {
                $entityManager->persist($offre);
                $entityManager->flush();

                $this->addFlash('success', 'L\'offre a été créée avec succès.');
                return $this->redirectToRoute('agent_offre_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur s\'est produite: ' . $e->getMessage());
            }
        }

        return $this->render('back/agent/offre/new.html.twig', [
            'banque' => $banque,
        ]);
    }

    #[Route('/edit/{id}', name: 'agent_offre_edit')]
    public function edit(Offre $offre, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Ensure the offer belongs to the agent's bank
        if ($offre->getBanque() !== $this->getUser()->getBanque()) {
            throw $this->createAccessDeniedException();
        }

        if ($request->isMethod('POST')) {
            $offre->setTitre($request->request->get('titre'));
            $offre->setDescription($request->request->get('description'));
            $offre->setTypeOffre($request->request->get('type_offre'));
            $offre->setConditions($request->request->get('conditions'));
            $offre->setTaux($request->request->get('taux'));
            $offre->setActive($request->request->get('active') === '1');

            // Dates
            $dateDebut = $request->request->get('date_debut');
            if ($dateDebut) {
                $offre->setDateDebut(\DateTime::createFromFormat('Y-m-d', $dateDebut));
            }
            
            $dateFin = $request->request->get('date_fin');
            if ($dateFin) {
                $offre->setDateFin(\DateTime::createFromFormat('Y-m-d', $dateFin));
            }

            try {
                $entityManager->flush();
                $this->addFlash('success', 'L\'offre a été modifiée avec succès.');
                return $this->redirectToRoute('agent_offre_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur s\'est produite: ' . $e->getMessage());
            }
        }

        return $this->render('back/offre/edit.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/delete/{id}', name: 'agent_offre_delete', methods: ['POST'])]
    public function delete(Offre $offre, EntityManagerInterface $entityManager): Response
    {
        // Ensure the offer belongs to the agent's bank
        if ($offre->getBanque() !== $this->getUser()->getBanque()) {
            throw $this->createAccessDeniedException();
        }

        try {
            $entityManager->remove($offre);
            $entityManager->flush();
            $this->addFlash('success', 'L\'offre a été supprimée avec succès.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Impossible de supprimer cette offre: ' . $e->getMessage());
        }

        return $this->redirectToRoute('agent_offre_index');
    }
}
