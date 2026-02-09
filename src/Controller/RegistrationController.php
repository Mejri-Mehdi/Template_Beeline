<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\BanqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        BanqueRepository $banqueRepository
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard_redirect');
        }

        $banques = $banqueRepository->findAll();

        if ($request->isMethod('POST')) {
            $user = new Utilisateur();
            $user->setEmail($request->request->get('email'));
            $user->setNom($request->request->get('nom'));
            $user->setPrenom($request->request->get('prenom'));
            $user->setTelephone($request->request->get('telephone'));
            
            // Hash password
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $request->request->get('plainPassword')
            );
            $user->setPassword($hashedPassword);

            // Determine role
            $role = $request->request->get('role', 'client');
            
            if ($role === 'agent') {
                $user->setRoles(['ROLE_AGENT']);
                $user->setStatutCompte('pending'); // Agents need admin approval
            } else {
                $user->setRoles(['ROLE_CLIENT']);
                $user->setStatutCompte('active'); // Clients are auto-approved
            }

            // Set bank
            $banqueId = $request->request->get('banque');
            if ($banqueId) {
                $banque = $banqueRepository->find($banqueId);
                if ($banque) {
                    $user->setBanque($banque);
                }
            }

            try {
                $entityManager->persist($user);
                $entityManager->flush();

                if ($user->getStatutCompte() === 'pending') {
                    $this->addFlash('success', 'Votre demande d\'inscription a été envoyée. Vous recevrez une notification une fois approuvée par un administrateur.');
                } else {
                    $this->addFlash('success', 'Inscription réussie! Vous pouvez maintenant vous connecter.');
                }

                return $this->redirectToRoute('app_login');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur s\'est produite lors de l\'inscription: ' . $e->getMessage());
            }
        }

        // Create a fake form object for template compatibility
        $formData = new class {
            public $nom;
            public $prenom;
            public $email;
            public $telephone;
            public $plainPassword;
            public $banque;
        };

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $formData,
            'banques' => $banques,
        ]);
    }
}