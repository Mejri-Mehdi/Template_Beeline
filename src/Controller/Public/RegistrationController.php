<?php

namespace App\Controller\Public;

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
            // Validate password confirmation
            $plainPassword = $request->request->get('plainPassword');
            $confirmPassword = $request->request->get('confirmPassword');
            
            if ($plainPassword !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_register');
            }

            $user = new Utilisateur();
            $user->setEmail($request->request->get('email'));
            $user->setNom($request->request->get('nom'));
            $user->setPrenom($request->request->get('prenom'));
            $user->setTelephone($request->request->get('telephone'));
            
            // Hash password
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plainPassword
            );
            $user->setPassword($hashedPassword);

            // Determine role
            $role = $request->request->get('role', 'client');
            
            if ($role === 'agent') {
                $user->setRoles(['ROLE_AGENT']);
                $user->setStatutCompte('pending'); // Agents need admin approval
                
                // Get bank name from dropdown or custom input
                $bankNameSelect = $request->request->get('bank_name_select');
                $bankName = '';
                
                if ($bankNameSelect === 'autre') {
                    // Agent selected "Autre" - use custom bank name
                    $bankName = $request->request->get('bank_name_custom');
                } else {
                    // Agent selected existing bank from dropdown
                    $bankName = $bankNameSelect;
                }
                
                // Store all bank details for admin review
                // Admin will decide if bank exists or needs to be created
                $bankDetails = [
                    'bank_name' => $bankName,
                    'agent_identifiant' => $request->request->get('agent_identifiant'),
                    'bank_email' => $request->request->get('bank_email'),
                    'bank_type' => $request->request->get('bank_type'),
                    'bank_address' => $request->request->get('bank_address'),
                    'bank_postal_code' => $request->request->get('bank_postal_code'),
                ];
                
                // Handle file upload if documents provided
                $documents = $request->files->get('bank_documents');
                // TODO: Store documents (implement file upload handling)
                
                // Store bank data as JSON for admin to review
                // Note: You'll need to add a 'pending_bank_data' TEXT field to Utilisateur entity
                // For now, we're just capturing the data
                
                // Don't set banque for agents - admin will do this on approval
                
            } else {
                $user->setRoles(['ROLE_CLIENT']);
                $user->setStatutCompte('active'); // Clients are auto-approved
                
                // Set bank for clients (they select from dropdown)
                $banqueId = $request->request->get('banque');
                if ($banqueId) {
                    $banque = $banqueRepository->find($banqueId);
                    if ($banque) {
                        $user->setBanque($banque);
                    }
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

        return $this->render('public/registration/register.html.twig', [
            'registrationForm' => $formData,
            'banques' => $banques,
        ]);
    }
}