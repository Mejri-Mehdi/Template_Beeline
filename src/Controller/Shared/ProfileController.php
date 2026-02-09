<?php

namespace App\Controller\Shared;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile_view')]
    public function view(): Response
    {
        $user = $this->getUser();
        
        return $this->render('shared/profile/view.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit', name: 'app_profile_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();

        if ($request->isMethod('POST')) {
            // Update basic info
            $user->setNom($request->request->get('nom'));
            $user->setPrenom($request->request->get('prenom'));
            $user->setEmail($request->request->get('email'));
            
            // Optional fields
            if ($request->request->get('telephone')) {
                $user->setTelephone($request->request->get('telephone'));
            }
            if ($request->request->get('adresse')) {
                $user->setAdresse($request->request->get('adresse'));
            }
            if ($request->request->get('cin')) {
                $user->setCin($request->request->get('cin'));
            }
            if ($request->request->get('code_postal')) {
                $user->setCodePostal($request->request->get('code_postal'));
            }

            // Handle photo upload
            $photoFile = $request->files->get('photo');
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/profiles',
                        $newFilename
                    );
                    $user->setPhoto($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de la photo.');
                }
            }

            try {
                $entityManager->flush();
                $this->addFlash('success', 'Profil mis à jour avec succès!');
                return $this->redirectToRoute('app_profile_view');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la mise à jour du profil.');
            }
        }

        return $this->render('shared/profile/edit.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/settings', name: 'app_profile_settings')]
    public function settings(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $this->getUser();

        if ($request->isMethod('POST')) {
            $currentPassword = $request->request->get('current_password');
            $newPassword = $request->request->get('new_password');
            $confirmPassword = $request->request->get('confirm_password');

            // Verify current password
            if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('error', 'Mot de passe actuel incorrect.');
                return $this->render('profile/settings.html.twig', ['user' => $user]);
            }

            // Check if new passwords match
            if ($newPassword !== $confirmPassword) {
                $this->addFlash('error', 'Les nouveaux mots de passe ne correspondent pas.');
                return $this->render('profile/settings.html.twig', ['user' => $user]);
            }

            // Check password strength
            if (strlen($newPassword) < 6) {
                $this->addFlash('error', 'Le mot de passe doit contenir au moins 6 caractères.');
                return $this->render('profile/settings.html.twig', ['user' => $user]);
            }

            // Hash and update password
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);

            try {
                $entityManager->flush();
                $this->addFlash('success', 'Mot de passe modifié avec succès!');
                return $this->redirectToRoute('app_profile_settings');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors du changement de mot de passe.');
            }
        }

        return $this->render('shared/profile/settings.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/photo/delete', name: 'app_profile_photo_delete', methods: ['POST'])]
    public function deletePhoto(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        if ($user->getPhoto()) {
            $photoPath = $this->getParameter('kernel.project_dir') . '/public/uploads/profiles/' . $user->getPhoto();
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
            $user->setPhoto(null);
            $entityManager->flush();
            $this->addFlash('success', 'Photo supprimée avec succès.');
        }

        return $this->redirectToRoute('app_profile_edit');
    }
}
