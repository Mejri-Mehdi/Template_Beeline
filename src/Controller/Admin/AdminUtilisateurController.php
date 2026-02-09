<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/utilisateurs')]
#[IsGranted('ROLE_ADMIN')]
class AdminUtilisateurController extends AbstractController
{
    #[Route('/', name: 'admin_users_index')]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        $users = $utilisateurRepository->findAll();

        return $this->render('admin/utilisateur/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/approve/{id}', name: 'admin_user_approve', methods: ['POST'])]
    public function approve(Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $utilisateur->setStatutCompte('active');
        $entityManager->flush();

        $this->addFlash('success', 'L\'utilisateur a été approuvé.');
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/reject/{id}', name: 'admin_user_reject', methods: ['POST'])]
    public function reject(Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $utilisateur->setStatutCompte('blocked');
        $entityManager->flush();

        $this->addFlash('success', 'L\'utilisateur a été rejeté.');
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/block/{id}', name: 'admin_user_block', methods: ['POST'])]
    public function block(Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $utilisateur->setStatutCompte('blocked');
        $entityManager->flush();

        $this->addFlash('success', 'L\'utilisateur a été bloqué.');
        return $this->redirectToRoute('admin_users_index');
    }

    #[Route('/unblock/{id}', name: 'admin_user_unblock', methods: ['POST'])]
    public function unblock(Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $utilisateur->setStatutCompte('active');
        $entityManager->flush();

        $this->addFlash('success', 'L\'utilisateur a été débloqué.');
        return $this->redirectToRoute('admin_users_index');
    }

    #[Route('/delete/{id}', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        // Prevent deleting self
        if ($utilisateur === $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            return $this->redirectToRoute('admin_users_index');
        }

        try {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
            $this->addFlash('success', 'L\'utilisateur a été supprimé.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Impossible de supprimer cet utilisateur: ' . $e->getMessage());
        }

        return $this->redirectToRoute('admin_users_index');
    }
}
