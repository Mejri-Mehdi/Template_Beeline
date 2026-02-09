<?php

namespace App\Controller\Front;

use App\Entity\RendezVous;
use App\Repository\RendezVousRepository;
use App\Repository\ServiceRepository;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/client/rendez-vous')]
#[IsGranted('ROLE_CLIENT')]
class ClientRendezVousController extends AbstractController
{
    #[Route('/book', name: 'client_rdv_book')]
    public function book(
        Request $request,
        ServiceRepository $serviceRepository,
        AgenceRepository $agenceRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $banque = $user->getBanque();

        if (!$banque) {
            $this->addFlash('error', 'Vous devez être associé à une banque pour prendre un rendez-vous.');
            return $this->redirectToRoute('client_dashboard');
        }

        $services = $serviceRepository->findAvailableByBanque($banque->getId());
        $agences = $agenceRepository->findByBanque($banque->getId());

        if ($request->isMethod('POST')) {
            $rdv = new RendezVous();
            $rdv->setClient($user);
            $rdv->setBanque($banque);

            // Get and set service
            $serviceId = $request->request->get('service_id');
            $service = $serviceRepository->find($serviceId);
            if ($service) {
                $rdv->setService($service);
            }

            // Get and set agence
            $agenceId = $request->request->get('agence_id');
            $agence = $agenceRepository->find($agenceId);
            if ($agence) {
                $rdv->setAgence($agence);
            }

            // Set date and time
            $date = \DateTime::createFromFormat('Y-m-d', $request->request->get('date_rdv'));
            $time = \DateTime::createFromFormat('H:i', $request->request->get('heure_rdv'));
            
            if ($date && $time) {
                $rdv->setDateRdv($date);
                $rdv->setHeureRdv($time);
                $rdv->setStatut('pending');

                try {
                    $entityManager->persist($rdv);
                    $entityManager->flush();

                    $this->addFlash('success', 'Votre rendez-vous a été demandé avec succès!');
                    return $this->redirectToRoute('client_rdv_ticket', ['id' => $rdv->getId()]);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur s\'est produite: ' . $e->getMessage());
                }
            } else {
                $this->addFlash('error', 'Date ou heure invalide.');
            }
        }

        return $this->render('front/rendez_vous/book.html.twig', [
            'services' => $services,
            'agences' => $agences,
            'banque' => $banque,
        ]);
    }

    #[Route('/my-appointments', name: 'client_rdv_list')]
    public function myAppointments(RendezVousRepository $rendezVousRepository): Response
    {
        $user = $this->getUser();
        $rendezVous = $rendezVousRepository->findByClient($user->getId());

        return $this->render('front/rendez_vous/my_appointments.html.twig', [
            'rendez_vous' => $rendezVous,
        ]);
    }

    #[Route('/ticket/{id}', name: 'client_rdv_ticket')]
    public function ticket(RendezVous $rendezVous): Response
    {
        // Ensure the ticket belongs to the current user
        if ($rendezVous->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        // TODO: Generate QR code if not already generated
        // This will be implemented when we add the QR code library

        return $this->render('front/rendez_vous/ticket.html.twig', [
            'rendez_vous' => $rendezVous,
        ]);
    }

    #[Route('/cancel/{id}', name: 'client_rdv_cancel', methods: ['POST'])]
    public function cancel(RendezVous $rendezVous, EntityManagerInterface $entityManager): Response
    {
        // Ensure the appointment belongs to the current user
        if ($rendezVous->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $rendezVous->setStatut('cancelled');
        $entityManager->flush();

        $this->addFlash('success', 'Votre rendez-vous a été annulé.');
        return $this->redirectToRoute('client_rdv_list');
    }
}
