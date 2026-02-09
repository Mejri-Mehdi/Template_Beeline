<?php

namespace App\Controller\Back;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/agent/service')]
#[IsGranted('ROLE_AGENT')]
class AgentServiceController extends AbstractController
{
    #[Route('/', name: 'agent_service_index')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        $user = $this->getUser();
        $banque = $user->getBanque();

        if (!$banque) {
            $this->addFlash('error', 'Vous devez être associé à une banque.');
            return $this->redirectToRoute('app_home');
        }

        $services = $serviceRepository->findByBanque($banque->getId());

        return $this->render('back/agent/service/index.html.twig', [
            'services' => $services,
            'banque' => $banque,
        ]);
    }

    #[Route('/new', name: 'agent_service_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $banque = $user->getBanque();

        if (!$banque) {
            $this->addFlash('error', 'Vous devez être associé à une banque.');
            return $this->redirectToRoute('app_home');
        }

        if ($request->isMethod('POST')) {
            $service = new Service();
            $service->setBanque($banque);
            $service->setNomService($request->request->get('nom_service'));
            $service->setDescription($request->request->get('description'));
            $service->setDureeEstimee((int)$request->request->get('duree_estimee'));
            $service->setDisponible($request->request->get('disponible') === '1');

            try {
                $entityManager->persist($service);
                $entityManager->flush();

                $this->addFlash('success', 'Le service a été créé avec succès.');
                return $this->redirectToRoute('agent_service_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur s\'est produite: ' . $e->getMessage());
            }
        }

        return $this->render('back/agent/service/new.html.twig', [
            'banque' => $banque,
        ]);
    }

    #[Route('/edit/{id}', name: 'agent_service_edit')]
    public function edit(Service $service, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Ensure the service belongs to the agent's bank
        if ($service->getBanque() !== $this->getUser()->getBanque()) {
            throw $this->createAccessDeniedException();
        }

        if ($request->isMethod('POST')) {
            $service->setNomService($request->request->get('nom_service'));
            $service->setDescription($request->request->get('description'));
            $service->setDureeEstimee((int)$request->request->get('duree_estimee'));
            $service->setDisponible($request->request->get('disponible') === '1');

            try {
                $entityManager->flush();
                $this->addFlash('success', 'Le service a été modifié avec succès.');
                return $this->redirectToRoute('agent_service_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur s\'est produite: ' . $e->getMessage());
            }
        }

        return $this->render('back/agent/service/edit.html.twig', [
            'service' => $service,
        ]);
    }

    #[Route('/delete/{id}', name: 'agent_service_delete', methods: ['POST'])]
    public function delete(Service $service, EntityManagerInterface $entityManager): Response
    {
        // Ensure the service belongs to the agent's bank
        if ($service->getBanque() !== $this->getUser()->getBanque()) {
            throw $this->createAccessDeniedException();
        }

        try {
            $entityManager->remove($service);
            $entityManager->flush();
            $this->addFlash('success', 'Le service a été supprimé avec succès.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Impossible de supprimer ce service: ' . $e->getMessage());
        }

        return $this->redirectToRoute('agent_service_index');
    }
}
