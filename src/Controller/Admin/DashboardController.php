<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Repository\HistoireCommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]

class DashboardController extends AbstractController
{


    #[Route('/', name: 'app_dashboard')]
    public function index(HistoireCommandeRepository $hst): Response
    {
        $nbOrder = $hst->getStatistique();
        
        return $this->render(
            'admin/dashboard.html.twig',
            [
                'nbOrder' => $nbOrder[0]['nbOrder'],
                'total' => $nbOrder[0]['total']
            ]
        );
    }
}
