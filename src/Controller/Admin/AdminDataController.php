<?php

namespace App\Controller\Admin;

use App\Repository\RendezVousRepository;
use App\Repository\OffreRepository;
use App\Repository\FinancementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminDataController extends AbstractController
{
    #[Route('/rendez-vous', name: 'admin_rdv_index')]
    public function rendezVous(RendezVousRepository $rendezVousRepository): Response
    {
        $rendezVous = $rendezVousRepository->findBy([], ['date_rdv' => 'DESC', 'heure_rdv' => 'DESC']);

        return $this->render('admin/rendez_vous/index.html.twig', [
            'rendez_vous' => $rendezVous,
        ]);
    }

    #[Route('/offres', name: 'admin_offres_index')]
    public function offres(OffreRepository $offreRepository): Response
    {
        $offres = $offreRepository->findAll();

        return $this->render('admin/offre/index.html.twig', [
            'offres' => $offres,
        ]);
    }

    #[Route('/financement', name: 'admin_financement_index')]
    public function financement(FinancementRepository $financementRepository): Response
    {
        $financements = $financementRepository->findBy([], ['date_demande' => 'DESC']);

        return $this->render('admin/financement/index.html.twig', [
            'financements' => $financements,
        ]);
    }

    #[Route('/services', name: 'admin_services_index')]
    public function services(\App\Repository\ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();

        return $this->render('admin/service/index.html.twig', [
            'services' => $services,
        ]);
    }
}
