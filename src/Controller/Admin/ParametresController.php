<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]

class ParametresController extends AbstractController
{
    #[Route('/parametres', name: 'app_parametres')]
    public function index(): Response
    {
        return $this->render('admin/parametres/index.html.twig', [
            'controller_name' => 'ParametresController',
        ]);
    }
}
