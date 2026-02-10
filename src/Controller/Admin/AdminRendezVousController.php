<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/rendez-vous')]
#[IsGranted('ROLE_ADMIN')]
class AdminRendezVousController extends AbstractController
{
    #[Route('/', name: 'admin_rendez_vous_index')]
    public function index(\App\Repository\RendezVousRepository $rendezVousRepository): Response
    {
        return $this->render('admin/rendez_vous/index.html.twig', [
            'rendez_vous' => $rendezVousRepository->findAll(),
        ]);
    }
}
