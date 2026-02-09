<?php

namespace App\Controller\Front;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/client/services')]
#[IsGranted('ROLE_CLIENT')]
class ClientServiceController extends AbstractController
{
    #[Route('/', name: 'client_services')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        $user = $this->getUser();
        $banque = $user->getBanque();

        if (!$banque) {
            $this->addFlash('warning', 'Vous devez être associé à une banque pour voir les services.');
            return $this->redirectToRoute('client_dashboard');
        }

        $services = $serviceRepository->findAvailableByBanque($banque->getId());

        return $this->render('front/service/index.html.twig', [
            'services' => $services,
            'banque' => $banque,
        ]);
    }
}
