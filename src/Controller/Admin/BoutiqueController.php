<?php

namespace App\Controller\Admin;

use App\Repository\BoutiqueRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoutiqueController extends AbstractController
{


    /**
     * index
     *
     * @param  mixed $repo
     * @param  mixed $request
     * @param  mixed $paginator
     * @return Response
     */

    #[Route('/admin/boutique', name: 'app_admin_boutique')]
    public function index(BoutiqueRepository $repo, Request $request, PaginatorInterface $paginator): Response
    {

        $boutiques = $paginator->paginate(
            $repo->findBy(
                array(),
                [
                    'nom' => 'desc'
                ]
            ),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'admin/boutique/index.html.twig',
            [
                'boutiques' => $boutiques
            ]
        );
    }



    /**
     * newBoutique
     *
     * @return Response
     */
    #[Route('boutique/new', name: 'app_new_boutique')]    
    public function newBoutique() : Response
    {
        return $this->render('admin/boutique/new.html.twig');
    }


    #[Route('boutique/store', name:'app_store_boutique')]
    public function store(Request $request)
    {
        return "koko";

    }
}
