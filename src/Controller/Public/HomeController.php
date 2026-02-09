<?php

namespace App\Controller\Public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // If user is logged in, redirect to their dashboard
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard_redirect');
        }

        return $this->render('public/home/index.html.twig');
    }
}