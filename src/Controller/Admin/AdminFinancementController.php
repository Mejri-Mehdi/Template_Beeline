<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/financement')]
#[IsGranted('ROLE_ADMIN')]
class AdminFinancementController extends AbstractController
{
    #[Route('/', name: 'admin_financement_index')]
    public function index(\App\Repository\FinancementRepository $financementRepository): Response
    {
        return $this->render('admin/financement/index.html.twig', [
            'financements' => $financementRepository->findAll(),
        ]);
    }
}
