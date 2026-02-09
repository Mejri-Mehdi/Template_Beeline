<?php

namespace App\Controller\Admin;

use App\Entity\Banque;
use App\Repository\BanqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/banques')]
#[IsGranted('ROLE_ADMIN')]
class AdminBanqueController extends AbstractController
{
    #[Route('/', name: 'admin_banques_index')]
    public function index(BanqueRepository $banqueRepository): Response
    {
        $banques = $banqueRepository->findAll();

        return $this->render('admin/banque/index.html.twig', [
            'banques' => $banques,
        ]);
    }

    #[Route('/approve/{id}', name: 'admin_banque_approve', methods: ['POST'])]
    public function approve(Banque $banque, EntityManagerInterface $entityManager): Response
    {
        $banque->setStatut('active');
        $entityManager->flush();

        $this->addFlash('success', 'La banque a été approuvée.');
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/reject/{id}', name: 'admin_banque_reject', methods: ['POST'])]
    public function reject(Banque $banque, EntityManagerInterface $entityManager): Response
    {
        $banque->setStatut('rejected');
        $entityManager->flush();

        $this->addFlash('success', 'La banque a été rejetée.');
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/delete/{id}', name: 'admin_banque_delete', methods: ['POST'])]
    public function delete(Banque $banque, EntityManagerInterface $entityManager): Response
    {
        try {
            $entityManager->remove($banque);
            $entityManager->flush();
            $this->addFlash('success', 'La banque a été supprimée.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Impossible de supprimer cette banque: ' . $e->getMessage());
        }

        return $this->redirectToRoute('admin_banques_index');
    }
}
