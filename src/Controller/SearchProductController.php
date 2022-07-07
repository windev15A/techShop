<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Filter;
use SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class SearchProductController extends AbstractController
{


    /**
     * repo
     *
     * @var ProductRepository
     */
    protected $repo;




    /**
     * __construct
     *
     * @param  ProductRepository $productRepository
     * @return void
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->repo =  $productRepository;
    }

    
    /**
     * search
     *
     * @param  Request $request
     * @return Response
     */
    #[Route('/search', name: 'app_search')]    
    public function search(Request $request)
    {


        $filter = new Filter();
        $form = $this->createForm(SearchType::class, $filter);
        $form->handleRequest($request);

        $products = $this->repo->recherche($filter);


        return $this->render('search.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }
}
