<?php

namespace App\Controller;

use App\Data\Filter;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SearchProductController extends AbstractController
{

    /**
     * repo
     *
     * @var ProductRepository
     */
    protected ProductRepository $repo;




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
    public function search(Request $request, PaginatorInterface $paginator): Response
    {
        $filter = new Filter();
        $form = $this->createForm(SearchType::class, $filter);
        $form->handleRequest($request);

        $products = $paginator->paginate(
            $this->repo->recherche($filter),
            $request->query->getInt('page', 1),
            10);
        return $this->render('search.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }
}
