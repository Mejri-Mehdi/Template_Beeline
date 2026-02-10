<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/offre')]
#[IsGranted('ROLE_ADMIN')]
class AdminOffreController extends AbstractController
{
    #[Route('/', name: 'admin_offre_index')]
    public function index(\App\Repository\OffreRepository $offreRepository): Response
    {
        return $this->render('admin/offre/index.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }
}
